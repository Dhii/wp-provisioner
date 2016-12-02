<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Utils;

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
