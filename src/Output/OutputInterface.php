<?php

namespace Dhii\WpProvision\Output;

/**
 * Represents structured output of a WP CLI command.
 *
 * @since [*next-version*]
 */
interface OutputInterface
{
    /**
     * @since [*next-version*]
     */
    const K_HEADER = 'header';
    /**
     * @since [*next-version*]
     */
    const K_FOOTER = 'footer';
    /**
     * @since [*next-version*]
     */
    const K_BODY = 'body';
    /**
     * @since [*next-version*]
     */
    const K_DATA = 'data';

    /**
     * Retrieve the header of the output.
     *
     * This typically contains a status message, or some other info,
     * but may contain also an error message.
     *
     * @since [*next-version*]
     *
     * @return string|null The header text, or null if no header.
     */
    public function getHeader();

    /**
     * Retrieve the footer of the output.
     *
     * This typically contains some conclusions, or legend, or coments and suggestions.
     *
     * @since [*next-version*]
     *
     * @return string The footer text, or null if no footer.
     */
    public function getFooter();

    /**
     * Retrieve the body of the output.
     *
     * @since [*next-version*]
     *
     * @return string
     */
    public function getBody();

    /**
     * Retrieve the full output text.
     *
     * @since [*next-version*]
     *
     * @return string The complete unaltered output.
     */
    public function getText();

    /**
     * Retrieve structured data that may be the result of parsing of the output.
     *
     * @since [*next-version*]
     *
     * @return mixed
     */
    public function getData();

    /**
     * Retrieve the string representation of this instance.
     *
     * @since [*next-version*]
     *
     * @return string The string representation of this instance.
     */
    public function __toString();
}
