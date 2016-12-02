<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Env;

/**
 * Interface Shell.
 */
interface ShellInterface
{
    /**
     * @param $command
     *
     * @return bool
     */
    public function commandExists($command);

    /**
     * Verify if a file exists and is executable.
     *
     * @param $file
     *
     * @return bool
     */
    public function isExecutable($file);
}
