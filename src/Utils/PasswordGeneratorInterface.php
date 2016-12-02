<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Utils;

/**
 * Interface PasswordGenerator.
 */
interface PasswordGeneratorInterface
{
    /**
     * @return string
     */
    public function generatePassword();
}
