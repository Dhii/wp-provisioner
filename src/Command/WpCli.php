<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Command;

use Dhii\WpProvision\Env;
use Dhii\WpProvision\Process;
use LogicException;
use Dhii\WpProvision\Api;
use Dhii\WpProvision\Output;
use Symfony\Component\Process\Process as CoreProcess;

/**
 * Wrapper for WP-CLI command.
 *
 * Usage example: To execute `wp site list` just run
 *
 * ( new WpCli() )->run( 'site list' );
 *
 * @since [*next-version*]
 */
class WpCli implements WpCliCommandInterface
{
    /**
     * @var string
     */
    private $base;

    /**
     * @var string
     */
    private $bin_path;

    /**
     * @var Env\ShellInterface
     */
    private $shell;

    /**
     * @var Process\ProcessBuilderInterface
     */
    private $process_builder;

    /**
     * @since [*next-version*]
     *
     * @param Env\ShellInterface $shell
     * @param string             $bin_path
     */
    public function __construct(
        Env\ShellInterface $shell,
        $bin_path = '',
        Process\ProcessBuilderInterface $process_builder = null
    ) {

        /*
         * Todo: remove this. We should rely only on paths to binaries provided by composer
         * and not on global installed versions of WP-CLI.
         */
        $this->shell = $shell;
        if ($bin_path) {
            $this->bin_path = realpath($bin_path);
            $this->base     = $bin_path;
        } else {
            $this->base = 'wp';
        }

        if (!$process_builder) {
            $process_builder = new Process\SymfonyProcessBuilderAdapter();
        }
        $this->process_builder = $process_builder;

        /*
         * This sucks as we cannot know if the process builder is used elsewhere.
         * Not sure if we should clone the object here or if we need an object-builder builder.
         */
        $this->process_builder->setPrefix($this->base());
    }

    /**
     * @since [*next-version*]
     *
     * @return string
     */
    public function base()
    {
        return $this->base;
    }

    /**
     * @since [*next-version*]
     *
     * @return bool
     */
    public function commandExists()
    {
        if ($this->bin_path) {
            return $this->shell->isExecutable($this->bin_path);
        }

        return $this->shell->commandExists($this->base);
    }

    /**
     * Run a WP CLI command, and retrieve its result.
     *
     * @since [*next-version*]
     *
     * @param array $arguments The arguments to add to the command.
     *                         They will be quoted, and joined together with a space.
     *
     * @return Api\CommandResultInterface The result of running the command.
     */
    public function run(array $arguments = [], $stdIn = null)
    {
        if (!$this->commandExists()) {
            throw new LogicException("The base command {$this->base()} does not exists or is not executable.");
        }

        $process = $this->process_builder
            ->setArguments([]) // reset the process builder state
            ->setArguments($arguments)
            ->getProcess();

        // A way to potentially provide data to the process while running
        if (!is_null($stdIn)) {
            $process->setInput($stdIn);
        }

        // Combined stdin and stderr output
        $output = '';
        $process->run(function ($type, $buffer) use (&$output) {
            $output .= $buffer;
        });

        // Uniform way of returning data about command result
        $result = $this->_createCommandResult($process, $output);

        return $result;
    }

    /**
     * Creates a new instance of an object that represents the result of a command.
     *
     * @since [*next-version*]
     *
     * @param CoreProcess                   $process The process that the command created.
     * @param string|Output\OutputInterface $output  Output of the process.
     * @param string                        $status  Status of the result.
     *
     * @return Api\CommandResultInterface This instance.
     */
    protected function _createCommandResult(CoreProcess $process,  $output = null, $status = null)
    {
        return new Api\CommandResult($process, $output, $status);
    }
}
