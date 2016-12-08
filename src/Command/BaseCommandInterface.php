<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Command;

use Dhii\WpProvision\Api\CommandResultInterface;

/**
 * Interface BaseCommand.
 *
 * Wraps a command (like `$ wp`) so you have to deal only with the arguments
 *
 * @since [*next-version*]
 */
interface BaseCommandInterface
{
    /**
     * @since [*next-version*]
     *
     * @return string
     */
    public function base();

    /**
     * @since [*next-version*]
     *
     * @return bool
     */
    public function commandExists();

    /**
     * @since [*next-version*]
     *
     * @param array $arguments
     *
     * @return string|CommandResultInterface
     */
    public function run(array $arguments = []);
}
