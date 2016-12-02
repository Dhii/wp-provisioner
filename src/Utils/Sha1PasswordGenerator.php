<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Utils;

/**
 * Class Sha1PasswordGenerator.
 *
 * @since [*next-version*]
 */
class Sha1PasswordGenerator implements PasswordGeneratorInterface
{
    /**
     * @var int
     */
    private $length = 20;

    /**
     * @since [*next-version*]
     *
     * @param int $length Values between 1 and 40 are valid
     */
    public function __construct($length = 20)
    {
        $length = (int) $length;
        if (1 > $length) {
            $length = 20;
        }
        if (40 < $length) {
            $length = 40;
        }

        $this->length = $length;
    }

    /**
     * @since [*next-version*]
     *
     * @return string
     */
    public function generatePassword()
    {
        $random = microtime() / mt_rand(1, time());

        return substr(sha1($random), 0, $this->length);
    }
}
