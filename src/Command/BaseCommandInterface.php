<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Command;

/**
 * Interface BaseCommand.
 *
 * Wraps a command (like `$ wp`) so you have to deal only with the arguments
 */
interface BaseCommandInterface
{
    /**
     * @return string
     */
    public function base();

    /**
     * @return bool
     */
    public function commandExists();

    /**
     * @param array $arguments
     *
     * @return string
     */
    public function run(array $arguments = array());
}
