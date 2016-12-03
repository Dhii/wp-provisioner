<?php

namespace Dhii\WpProvision\Wp;

use RuntimeException;
use Dhii\WpProvision\Command;

/**
 * Provides means of manipulating WP themes.
 *
 * @since [*next-version*]
 */
class Theme extends CommandBase implements ThemeInterface
{
    const CMD        = 'theme';
    const CMD_STATUS = 'status';

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

    public function activate($theme, $options = [])
    {
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
     * @param type $theme
     * @param type $options
     *
     * @return mixed[] An array with status data. The structure is different depending on whether the $theme is passed.
     *                 If present, the result is an array with
     */
    public function getStatus($theme = null, $options = [])
    {
        $args = [
            static::CMD_STATUS,
            $this->_nullIfEmpty($theme),
        ];

        $options = $this->_removeEmpty($options);
        $parts   = $this->_prepareCmdParts($args, $options);
        $output  = $this->_getWpCli()->run($parts);

        $info = is_null($theme)
            ? $this->_parseMultipleStatusOutput($output)
            : $this->_parseSingleStatusOutput($output);

        return $info;
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

    protected function _parseSingleStatusOutput($output)
    {
        $output = trim($output);
        $lines  = explode(PHP_EOL, $output);
        $header = array_shift($lines);
        $err    = 'Could not parse single theme status output';

        $matches = [];
        if (!preg_match('!Theme ([^\s]+) details!', $header, $matches)) {
            throw new RuntimeException(sprintf('%1$s: header "%2$s" could not be matched', $err, $header));
        }

        $slug = $matches[1];

        $info = [];
        foreach ($lines as $_idx => $_line) {
            $_line = trim($_line);
            $parts = explode(':', $_line);
            if (count($parts) < 2) {
                throw new RuntimeException(sprintf('%1$s: line %2$d format not recognized: %3$s', $err, $_idx, $_line));
            }
            $key   = trim($parts[0]);
            $value = trim($parts[1]);

            $key = str_ireplace(['-', ' '], '_', $key);
            $key = strtolower($key);

            $info[$key] = $value;
        }

        $info[self::K_SLUG]   = $slug;
        $info[self::K_STATUS] = $this->_normalizeStatusString($info[self::K_STATUS]);

        return $info;
    }

    protected function _parseMultipleStatusOutput($output)
    {
        $output = trim($output);
        $lines  = explode(str_repeat(PHP_EOL, 2), $output);
        $footer = array_pop($lines);
        $lines  = explode(PHP_EOL, trim(array_shift($lines)));
        $header = array_shift($lines);
        $err    = 'Could not parse multiple themes status output';

        $info = [];
        foreach ($lines as $_idx => $_line) {
            $_line = trim($_line);
            var_dump($_line);
            $parts = preg_split('![\s]+!', $_line);
            if (count($parts) < 3) {
                throw new RuntimeException(sprintf('%1$s: line %2$d format not recognized: %3$s', $err, $_idx, $_line));
            }

            $status  = $this->_normalizeStatusString($parts[0]);
            $slug    = trim($parts[1]);
            $version = trim($parts[2]);

            $info[$slug] = [
                self::K_SLUG    => $slug,
                self::K_STATUS  => $status,
                self::K_VERSION => $version,
            ];
        }

        return $info;
    }

    /**
     * Converts a status string into one of the defined representations.
     *
     * @since [*next-version*]
     *
     * @param string $status One of the STATUS_* class constants.
     */
    protected function _normalizeStatusString($status)
    {
        $status = strtolower(trim($status));

        if (in_array($status, ['a', 'active'], true)) {
            $status = self::STATUS_ACTIVE;
        } elseif (in_array($status, ['i', 'inactive', true])) {
            $status = self::STATUS_INACTIVE;
        }

        return $status;
    }
}
