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

        $statiDetected = $this->_detectTokens($status, $statiToCheck);

        return $statiDetected;
    }

    /**
     * Determine the tokens from a pre-defined set which are present in a string.
     *
     * @since [*next-version*]
     *
     * @param string $string The string, where tokens should be detected.
     * @param array[] $map A map of token codes to their representations.
     *  The key is token code.
     *  The value is an array of possible representations.
     * @return string All tokens found in the string.
     */
    protected function _detectTokens($string, $map)
    {
        $d = '!';
        $dictionary = [];
        foreach ($map as $_token => $_values) {
            foreach ($_values as $_value) {
                $dictionary[$_value] = $_token;
            }
        }

        // Sort representations by longest first
        uksort($dictionary, function($a, $b) {
            $lenA = strlen($a);
            $lenB = strlen($b);

            if ($lenA === $lenB) {
                return 0;
            }

            return $lenA > $lenB
                ? -1
                : 1;
        });

        $detected = [];
        foreach ($dictionary as $_value => $_token) {
            $count = 0;
            $pattern = $d . preg_quote($_value, $d) . $d . 'm';
            preg_replace($pattern, '', $string, 1, $count);
            if ($count) {
                $detected[$_token] = $_token;
            }
        }

        return $detected;
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
