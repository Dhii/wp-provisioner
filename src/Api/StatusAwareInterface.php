<?php

namespace Dhii\WpProvision\Api;

/**
 * Something that can have a status retrieved.
 *
 * @since [*next-version*]
 */
interface StatusAwareInterface
{
    const STATUS_INFO    = 'info';
    const STATUS_SUCCESS = 'success';
    const STATUS_WARNING = 'warn';
    const STATUS_ERROR   = 'error';

    /**
     * A status code of the command.
     *
     * @since [*next-version*]
     *
     * @return string|null
     */
    public function getStatus();
}
