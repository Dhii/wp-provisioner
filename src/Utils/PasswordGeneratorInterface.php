<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Utils;

/**
 * Interface PasswordGenerator.
 *
 * @since [*next-version*]
 */
interface PasswordGeneratorInterface
{
    /**
     * @since [*next-version*]
     *
     * @return string
     */
    public function generatePassword();
}
