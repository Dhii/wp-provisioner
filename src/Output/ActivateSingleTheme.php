<?php

namespace Dhii\WpProvision\Output;

/**
 * Represents the output of activation of a single theme.
 *
 * @since [*next-version*]
 */
class ActivateSingleTheme extends AbstractOutputBase
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _parse($output)
    {
        $message = $this->_createStatusMessage($output);

        return [
            self::K_HEADER => (string) $output,
            self::K_BODY   => (string) $output,
            self::K_FOOTER => null,
            self::K_DATA   => $message,
        ];
    }
}
