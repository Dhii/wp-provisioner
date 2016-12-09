<?php

namespace Dhii\WpProvision\Output;

/**
 * Something that can represent a WP theme status.
 *
 * @since [*next-version*]
 */
interface ThemeStatusAwareInterface
{
    /**
     * Retrieve the status code of this instance.
     *
     * @since [*next-version*]
     *
     * @return string The status code.
     */
    public function getStatus();
}
