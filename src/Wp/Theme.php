<?php

namespace Dhii\WpProvision\Wp;

use RuntimeException;
use Dhii\WpProvision\Command;
use Dhii\WpProvision\Output;

/**
 * Provides means of manipulating WP themes.
 *
 * @since [*next-version*]
 */
class Theme extends CommandBase implements ThemeInterface
{
    const CMD          = 'theme';
    const CMD_STATUS   = 'status';
    const CMD_ACTIVATE = 'activate';

    /**
     * @since [*next-version*]
     *
     * @param Command\WpCliCommandInterface $wpCli
     */
    public function __construct(Command\WpCliCommandInterface $wpCli)
    {
        $this->wpCli = $wpCli;
        $this->_construct();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function activate($theme, $options = [])
    {
        $args = [
            static::CMD_ACTIVATE,
            trim($theme),
        ];

        $options = $this->_removeEmpty($options);
        $parts   = $this->_prepareCmdParts($args, $options);

        $me     = $this;
        $result = $this->_runCommand(function () use ($me, $parts) {
            $result = $me->_getWpCli()->run($parts);
            $output = $result->getOutput();
            $output = $me->_parseActivationOutput($output);
            $res = $me->_createCommandResult($result->getProcess(), $output);

            return $res;
        });

        return $result;
    }

    public function delete($theme, $options = [])
    {
    }

    public function getAll($options = [])
    {
    }

    public function getDetails($theme, $options = [])
    {
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getStatus($theme = null, $options = [])
    {
        $args = [
            static::CMD_STATUS,
            $this->_nullIfEmpty($theme),
        ];

        $options = $this->_removeEmpty($options);
        $parts   = $this->_prepareCmdParts($args, $options);
        $me      = $this;

        $res = $this->_runCommand(function () use ($me, $parts, $theme) {
            $result = $me->_getWpCli()->run($parts);
            $output = $result->getOutput();

            $output = is_null($theme)
                ? $this->_parseMultipleStatusOutput($output)
                : $this->_parseSingleStatusOutput($output);
            $res = $me->_createCommandResult($result->getProcess(), $output);

            return $res;
        });

        return $res;
    }

    public function install($theme, $options = [])
    {
    }

    public function isInstalled($theme, $options = [])
    {
    }

    public function update($theme, $options = [])
    {
    }

    /**
     * Parse the output of a status check on a sinlge theme.
     *
     * @since [*next-version*]
     *
     * @param string $output The output of the status check.
     *
     * @throws RuntimeException If output cannot be parsed.
     *
     * @return Output\OutputInterface
     */
    protected function _parseSingleStatusOutput($output)
    {
        return new Output\ThemeStatusSingle($output);
    }

    /**
     * Parse the output of a status check on multiple themes.
     *
     * @since [*next-version*]
     *
     * @param string $output The output of the status check.
     *
     * @throws RuntimeException If output cannot be parsed.
     *
     * @return Output\OutputInterface
     */
    protected function _parseMultipleStatusOutput($output)
    {
        $output = new Output\ThemeStatusMultiple($output);

        return $output;
    }

    /**
     * Converts theme activation output into a standardized data structure.
     *
     * @since [*next-version*]
     *
     * @param string $output Output of a theme activation command.
     *
     * @return Output\OutputInterface The parsed output.
     */
    protected function _parseActivationOutput($output)
    {
        $output = new Output\ActivateSingleTheme($output);

        return $output;
    }
}
