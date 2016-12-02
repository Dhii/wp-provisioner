<?php

namespace Dhii\WpProvision\Wp;

/**
 * Something that can act as a WP command.
 *
 * @since [*next-version*]
 */
interface CommandInterface
{
    /**
     * Retrieve the main command that this command represents.
     *
     * @since [*next-version*]
     *
     * @return string The main command text.
     */
    public function getMainCommand();
}
