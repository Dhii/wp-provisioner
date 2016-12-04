<?php

namespace Dhii\WpProvision\Output;

use Dhii\WpProvision\Api;

/**
 * Something that can represent a WP CLI status message.
 *
 * @since [*next-version*]
 */
interface StatusMessageInterface extends Api\StatusAwareInterface
{
    /**
     * Retrieve the complete text of the status message.
     *
     * @since [*next-version*]
     *
     * @return string The complete text of the output.
     */
    public function getText();

    /**
     * Retrieve only the message part of the status message.
     *
     * @since [*next-version*]
     *
     * @return string The message part.
     */
    public function getMessage();

    /**
     * Retrieve the string representation of this message.
     *
     * @since [*next-version*]
     *
     * @return string The string representation of this instance.
     */
    public function __toString();
}
