<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Command;

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
     * @return string
     */
    public function run(array $arguments = array());
}
