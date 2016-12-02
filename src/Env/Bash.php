<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Env;

use Dhii\WpProvision\Process;

/**
 * Class Bash.
 *
 * @since [*next-version*]
 */
class Bash implements ShellInterface
{
    /**
     * @var Process\ProcessBuilderInterface
     */
    private $processBuilder;

    /**
     * @since [*next-version*]
     *
     * @param Process\ProcessBuilderInterface $processBuilder
     */
    public function __construct(Process\ProcessBuilderInterface $processBuilder)
    {
        $this->processBuilder = $processBuilder;
    }

    /**
     * @since [*next-version*]
     *
     * @param $command
     *
     * @return bool
     */
    public function commandExists($command)
    {
        $process = $this
            ->processBuilder
            ->setArguments(
                array(
                    'hash',
                    $command,
                    '2>/dev/null || echo "false"',
                )
            )
            ->getProcess();

        $output = $process
            ->mustRun()
            ->getOutput();

        return 'false' !== trim($output);
    }

    /**
     * Verify if a file exists and is executable.
     *
     * @since [*next-version*]
     *
     * @param $file
     *
     * @return bool
     */
    public function isExecutable($file)
    {
        return file_exists($file) && is_executable($file);
    }
}
