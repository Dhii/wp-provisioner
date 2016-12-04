<?php

namespace Dhii\WpProvision\Output;

/**
 * Something that can represent a WP theme status.
 *
 * @since [*next-version*]
 */
interface ThemeStatusAwareInterface
{
    public function getStatus();
}
