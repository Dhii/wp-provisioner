<?php

namespace Dhii\WpProvision\Output;

use Dhii\WpProvision\Model\ThemeInterface;

/**
 * Represents output of a status check on multiple themes.
 *
 * @since [*next-version*]
 */
class ThemeStatusMultiple extends AbstractThemeStatus
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _parse($output)
    {
        $output = trim($output);
        $lines  = explode(str_repeat(PHP_EOL, 2), $output);
        $footer = array_pop($lines);
        $body   = $lines;
        $lines  = explode(PHP_EOL, trim(array_shift($body)));
        $header = array_shift($lines);
        $err    = 'Could not parse multiple themes status output';

        $kSlug    = ThemeInterface::K_SLUG;
        $kVersion = ThemeInterface::K_VERSION;
        $kStatus  = ThemeInterface::K_STATUS;
        $kUpdates = ThemeInterface::K_UPDATES;

        $info = [];
        foreach ($lines as $_idx => $_line) {
            $_line = trim($_line);
            $parts = preg_split('![\s]+!', $_line);
            if (count($parts) < 3) {
                throw new ParsingException(sprintf('%1$s: line %2$d format not recognized: %3$s', $err, $_idx, $_line));
            }

            $statusSet  = $this->_normalizeStatusString($parts[0]);
            $slug    = trim($parts[1]);
            $version = trim($parts[2]);

            $status = ThemeInterface::STATUS_UNKNOWN;
            if (in_array(ThemeInterface::STATUS_ACTIVE, $statusSet, true)) {
                $status = ThemeInterface::STATUS_ACTIVE;
            }
            elseif (in_array(ThemeInterface::STATUS_INACTIVE, $statusSet, true)) {
                $status = ThemeInterface::STATUS_INACTIVE;
            }

            $updateStatus = ThemeInterface::STATUS_UNKNOWN;
            if (in_array(ThemeInterface::UPDATE_AVAILABLE, $statusSet, true)) {
                $updateStatus = ThemeInterface::UPDATE_AVAILABLE;
            }
            elseif (in_array(ThemeInterface::UPDATE_UNAVAILABLE, $statusSet, true)) {
                $updateStatus = ThemeInterface::UPDATE_UNAVAILABLE;
            }

            $info[$slug] = $this->_createTheme([
                $kSlug    => $slug,
                $kStatus  => $status,
                $kVersion => $version,
                $kUpdates => $updateStatus,
            ]);
        }

        $info = [
            self::K_HEADER => $header,
            self::K_BODY   => $body,
            self::K_FOOTER => $footer,
            self::K_DATA   => $info,
        ];

        return $info;
    }
}
