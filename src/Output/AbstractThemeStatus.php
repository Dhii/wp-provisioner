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
     * Converts a status string into an array of recognized normalized representations.
     *
     * @since [*next-version*]
     *
     * @param string[] $status A set of STATUS_* class constants.
     *  If status unrecognized, array is empty.
     */
    protected function _normalizeStatusString($status)
    {
        $origStatus = $status;
        $status     = strtolower(trim($status));

        $statiDetected = [];
        $statiToCheck = [
            Model\ThemeInterface::STATUS_INACTIVE       => [
                Model\ThemeInterface::STATUS_INACTIVE,
                'i',
                'inactive'
            ],
            Model\ThemeInterface::STATUS_ACTIVE         => [
                Model\ThemeInterface::STATUS_ACTIVE,
                'a',
                'active'
            ],
            Model\ThemeInterface::STATUS_UPDATE         => [
                Model\ThemeInterface::STATUS_UPDATE,
                'u',
                'update',
                'available'
            ],
        ];

        $d = '!';
        foreach ($statiToCheck as $_detected => $_toCheck) {
            $count = 0;
            array_walk($_toCheck, function(&$v, $k) use ($d) { return preg_quote($v, $d); });
            $regex = $d . implode('|', $_toCheck) . $d . 'im';
            $status = preg_replace($regex, '', $status, -1, $count);

            if ($count) {
                $statiDetected[$_detected] = $_detected;
            }
        }

        return $statiDetected;
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
