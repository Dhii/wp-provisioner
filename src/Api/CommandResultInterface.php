<?php

namespace Dhii\WpProvision\Api;

/**
 * Something that can represent a result of running a WP CLI command.
 *
 * @since [*next-version*]
 */
interface CommandResultInterface
{
    const MSG_STATUS_INFO    = 'info';
    const MSG_STATUS_SUCCESS = 'success';
    const MSG_STATUS_WARNING = 'warn';
    const MSG_STATUS_ERROR   = 'error';

    /**
     * A status message of the command.
     *
     * @since [*next-version*]
     *
     * @return string|null
     */
    public function getMessage();

    /**
     * The original output of the command.
     *
     * @since [*next-version*]
     *
     * @return string The command output.
     */
    public function getText();

    /**
     * A status code of the command.
     *
     * @since [*next-version*]
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Data retrieved from the command.
     *
     * @since [*next-version*]
     *
     * @return mixed[][]
     */
    public function getData();

    /**
     * Determine of the command, of which this is a result, was a success.
     *
     * @since [*next-version*]
     *
     * @return bool True if this instance represents a successful result;
     *              false otherwise.
     */
    public function isSuccess();
}
