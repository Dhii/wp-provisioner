<?php

namespace Dhii\WpProvision\Output;

use Dhii\WpProvision\Model;

/**
 * Common functionality for theme status output summaries.
 *
 * @since [*next-version*]
 */
abstract class AbstractThemeStatus extends AbstractOutputBase
{
    /**
     * Converts a status string into one of the defined representations.
     *
     * @since [*next-version*]
     *
     * @param string $status One of the STATUS_* class constants.
     */
    protected function _normalizeStatusString($status)
    {
        $origStatus = $status;
        $status     = strtolower(trim($status));

        $statusActive   = Model\ThemeInterface::STATUS_ACTIVE;
        $statusInactive = Model\ThemeInterface::STATUS_INACTIVE;

        if (in_array($status, ['a', 'active', $statusActive], true)) {
            $status = $statusActive;
        } elseif (in_array($status, ['i', 'inactive', $statusInactive], true)) {
            $status = $statusInactive;
        } else {
            $status = $origStatus;
        }

        return $status;
    }

    /**
     * Creates a new theme object.
     *
     * @since [*next-version*]
     *
     * @param mixed[] $data Data of the theme.
     * 
     * @return Model\Theme The new theme.
     */
    protected function _createTheme($data)
    {
        return new Model\Theme($data);
    }
}
