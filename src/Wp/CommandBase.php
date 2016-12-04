<?php

namespace Dhii\WpProvision\Wp;

use RuntimeException;
use Dhii\WpProvision\Api;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Dhii\WpProvision\Command;
use Dhii\WpProvision\Output;

/**
 * A base for all commands that expose a common command interface.
 *
 * Declares and contains normalizers for all global WP CLI options.
 *
 * @since [*next-version*]
 */
abstract class CommandBase extends AbstractCommand implements CommandInterface
{
    /**
     * @since [*next-version*]
     *
     * @var mixed[]
     */
    protected $optionKeyMap;

    /**
     * @since [*next-version*]
     *
     * @var mixed[]
     */
    protected $optionValueMap;

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getMainCommand()
    {
        return $this->_getMainCommand();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getOptionKeyMap()
    {
        if (is_null($this->optionKeyMap)) {
            $this->optionKeyMap = array_merge(parent::_getOptionKeyMap(), [
                'path'          => 'path',
                'ssh'           => 'ssh',
                'http'          => 'http',
                'url'           => 'url',
                'user'          => 'user',
                'skip_plugins'  => 'skip-plugins',
                'skip_themes'   => 'skip-themes',
                'skip_packages' => 'skip-packages',
                'require'       => 'require',
                'color'         => 'color',
                'no_color'      => 'no-color',
                'debug'         => 'debug',
                'prompt'        => 'prompt',
                'quiet'         => 'quiet',
            ]);
        }

        return $this->optionKeyMap;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getOptionValueMap()
    {
        if (is_null($this->optionValueMap)) {
            $this->optionValueMap = array_merge(parent::_getOptionKeyMap(), [
                'path' => [
                    $this->_createCommand([$this, '_normalizeTrim']),
                ],
                'ssh' => [
                    $this->_createCommand([$this, '_normalizeSshOption']),
                ],
                'http' => [
                    $this->_createCommand([$this, '_normalizeTrim']),
                ],
                'url' => [
                    $this->_createCommand([$this, '_normalizeTrim']),
                ],
                'user' => [
                    $this->_createCommand([$this, '_normalizeTrim']),
                ],
                'skip_plugins' => [
                    $this->_createCommand([$this, '_normalizeBoolOrList']),
                ],
                'skip_themes' => [
                    $this->_createCommand([$this, '_normalizeBoolOrList']),
                ],
                'skip_packages' => [
                    $this->_createCommand([$this, '_normalizeBoolean']),
                ],
                'require' => [
                    $this->_createCommand([$this, '_normalizeMultipleUse']),
                ],
                'color' => [
                    $this->_createCommand([$this, '_normalizeBool']),
                ],
                'no_color' => [
                    $this->_createCommand([$this, '_normalizeBool']),
                ],
                'debug' => [
                    $this->_createCommand([$this, '_normalizeBoolOrList']),
                ],
                'prompt' => [
                    $this->_createCommand([$this, '_normalizeBoolOrList']),
                ],
                'quiet' => [
                    $this->_createCommand([$this, '_normalizeBool']),
                ],
            ]);
        }

        return $this->optionValueMap;
    }

    /**
     * Runs a command, abstracting details.
     *
     * @since [*next-version*]
     *
     * @param callable $cmd A callable that runs a CLI command.
     *
     * @return Api\CommandResultInterface The command result.
     */
    protected function _runCommand($cmd)
    {
        if (!is_callable($cmd)) {
            throw new RuntimeException(sprintf('Could not run command: command must be callable'));
        }

        $status  = null;
        $message = null;
        $result  = null;
        ob_start();
        try {
            $result = call_user_func_array($cmd, []);
        } catch (ProcessFailedException $e) {
            echo $e->getProcess()->getErrorOutput();
            $status = Api\StatusAwareInterface::STATUS_ERROR;
        }

        $output = ob_get_clean();
        $data   = $result;

        if ($result instanceof Api\CommandResultInterface) {
            if (!is_null($cmdOut = $result->getText())) {
                $output = $cmdOut;
            }
            if (!is_null($cmdMessage = $result->getMessage())) {
                $message = $cmdMessage;
            }
            // Only set if not null to preserve error status if exception thrown
            if (is_null($status) && !is_null($cmdStatus = $result->getStatus())) {
                $status = $cmdStatus;
            }
            $data = $result->getData();
        }

        if (is_null($message) && $data instanceof Output\StatusMessageInterface) {
            $message = $data;
        }
        if (is_null($status) && $message instanceof Api\StatusAwareInterface) {
            $status = $message->getStatus();
        }

        $result = $this->_createCommandResult($status, $message, $data, $output);

        return $result;
    }

    /**
     * Creates a new instance of an object that represents the result of a command.
     *
     * @since [*next-version*]
     *
     * @param string  $status  Status of the command. One of the {@see CommandResultInterface}::MSG_STATUS_* constants.
     * @param string  $message Status message.
     * @param mixed[] $data    Data passed from the command. Usually the result of parsing.
     * @param string  $text    Full output of the command.
     *
     * @return Api\CommandResult
     */
    protected function _createCommandResult($status, $message = null, $data = [], $text = null)
    {
        return new Api\CommandResult($status, $message, $data, $text);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _createOutput($text)
    {
        return new Command\GenericOutput($text);
    }
}
