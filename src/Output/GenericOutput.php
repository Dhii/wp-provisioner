<?php

namespace Dhii\WpProvision\Output;

/**
 * A generic output summary.
 *
 * @since [*next-version*]
 */
class GenericOutput extends AbstractOutputBase
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function _parse($output)
    {
        return [
            self::K_HEADER => null,
            self::K_BODY   => $output,
            self::K_FOOTER => null,
        ];
    }
}
