<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Env;

/**
 * Interface Shell.
 *
 * @since [*next-version*]
 */
interface ShellInterface
{
    /**
     * @since [*next-version*]
     *
     * @param $command
     *
     * @return bool
     */
    public function commandExists($command);

    /**
     * Verify if a file exists and is executable.
     *
     * @since [*next-version*]
     *
     * @param $file
     *
     * @return bool
     */
    public function isExecutable($file);
}
