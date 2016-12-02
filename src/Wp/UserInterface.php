<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Wp;

use Exception;

/**
 * @since [*next-version*]
 */
interface UserInterface
{
    /**
     * @since [*next-version*]
     *
     * @param string $email_or_login
     *
     * @return int
     */
    public function userId($email_or_login);

    /**
     * @since [*next-version*]
     *
     * @param bool $email_or_login
     *
     * @return bool
     */
    public function exists($email_or_login);

    /**
     * @since [*next-version*]
     *
     * @param $login
     * @param $email
     * @param array  $attributes
     *                           string $attributes[ 'role' ]
     *                           string $attributes[ 'password' ]
     *                           string $attributes[ 'first_name' ]
     *                           string $attributes[ 'last_name' ]
     *                           string $attributes[ 'display_name' ]
     *                           bool $attributes[ 'send_mail' ]
     *                           DateTimeInterface $attributes[ 'registered_at' ]
     * @param string $site_url
     * @param bool   $graceful   Set to FALSE to throw exceptions when something goes wrong (e.g. the user already exists)
     *
     * @throws Exception
     *
     * @return int
     */
    public function create($login, $email, array $attributes = array(), $site_url = '', $graceful = true);
}
