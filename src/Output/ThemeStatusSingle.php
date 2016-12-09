<?php

namespace Dhii\WpProvision\Output;

use Dhii\WpProvision\Model\ThemeInterface;

/**
 * Represents output of a status check on a single theme.
 *
 * @since [*next-version*]
 */
class ThemeStatusSingle extends AbstractThemeStatus
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _parse($output)
    {
        $output = trim($output);
        $lines  = explode(PHP_EOL, $output);
        $header = array_shift($lines);
        $body   = implode(PHP_EOL, $lines);
        $err    = 'Could not parse single theme status output';

        $kSlug    = ThemeInterface::K_SLUG;
        $kVersion = ThemeInterface::K_VERSION;
        $kStatus  = ThemeInterface::K_STATUS;
        $kUpdates = ThemeInterface::K_UPDATES;

        $matches = [];
        if (!preg_match('!Theme ([^\s]+) details!', $header, $matches)) {
            throw new ParsingException(sprintf('%1$s: header "%2$s" could not be matched', $err, $header));
        }

        $slug = $matches[1];

        $info = [];
        foreach ($lines as $_idx => $_line) {
            $_line = trim($_line);
            $parts = explode(':', $_line);
            if (count($parts) < 2) {
                throw new ParsingException(sprintf('%1$s: line %2$d format not recognized: %3$s', $err, $_idx, $_line));
            }
            $key   = trim($parts[0]);
            $value = trim($parts[1]);

            $key = str_ireplace(['-', ' '], '_', $key);
            $key = strtolower($key);

            $info[$key] = $value;
        }

        $info[$kSlug] = $slug;

        $stati          = $this->_normalizeStatusString($info[$kStatus]);
        $info[$kStatus] = $this->_getStatusFromSet($stati);

        $versionString = $info[$kVersion];
        $versionPieces = explode('(', $versionString);
        // The version number itself
        if (isset($versionPieces[0])) {
            $info[$kVersion] = trim($versionPieces[0]);
        }

        $updateStatus = ThemeInterface::STATUS_UNKNOWN;
        if (isset($versionPieces[1])) {
            $matches = [];
            preg_match('!update ([\w]*)!mi', $versionPieces[1], $matches);
            if (count($matches[1])) {
                $updateStati  = $this->_normalizeStatusString($matches[1]);
                $updateStatus = $this->_getUpdateStatusFromSet($updateStati);
            }
        }
        $info[$kUpdates] = $updateStatus;

        $info = [$slug => $this->_createTheme($info)];
        $info = [
            self::K_HEADER => $header,
            self::K_BODY   => $body,
            self::K_FOOTER => null,
            self::K_DATA   => $info,
        ];

        return $info;
    }
}
