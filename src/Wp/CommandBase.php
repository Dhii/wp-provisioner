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
    const MSG_K_STATUS = 'status';
    const MSG_K_TEXT   = 'text';

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
     * Converts a WP CLI status message into a structure.
     *
     * Many times, WP CLI will output a status message after running a command, such as:
     * Success: Theme 'twentysixtee' activated.
     *
     * This method helps determine what happened, based on that message.
     *
     * @since [*next-version*]
     *
     * @param string $message The status message to parse.
     * @return string[] An info array with 2 members:
     * MSG_K_STATUS and MSG_K_TEXT, which contain the status and the text of the status message respectively.
     * @throws RuntimeException
     */
    public function _parseWpcliStatusMessage($message)
    {
        $message = trim($message);
        $parts   = explode(':', $message);
        if (!count($parts)) {
            throw new RuntimeException(sprintf('Could not parse WP CLI status message: %1$s', $message));
        }

        $text = array_shift($parts);
        if (count($parts) > 0) {
            $status = $text;
            $text   = array_shift($parts);
        }

        $info = [
            self::MSG_K_STATUS => trim($status),
            self::MSG_K_TEXT   => trim($text),
        ];

        return $info;
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

        $status  = Api\CommandResultInterface::MSG_STATUS_INFO;
        $message = null;
        $result  = null;
        ob_start();
        try {
            $result = call_user_func_array($cmd, []);
        } catch (ProcessFailedException $e) {
            $status = Api\CommandResultInterface::MSG_STATUS_ERROR;
        }

        $output = ob_end_clean();
        $data   = $result;

        if ($result instanceof Api\CommandResultInterface) {
            if (!is_null($cmdOut = $result->getText())) {
                $output = $cmdOut;
            }
            if (!is_null($cmdMessage = $result->getMessage())) {
                $message = $cmdMessage;
            }
            if (!is_null($cmdStatus = $result->getStatus())) {
                $status = $cmdStatus;
            }
            $data = $result->getData();
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
