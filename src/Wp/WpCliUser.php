<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Wp;

use Dhii\WpProvision\Command;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;

/**
 * Class WpCliUser.
 *
 * @since [*next-version*]
 */
class WpCliUser implements UserInterface
{
    /**
     * @var Command\WpCliCommandInterface
     */
    private $wp_cli;

    /**
     * @since [*next-version*]
     *
     * @param Command\WpCliCommandInterface $wp_cli
     */
    public function __construct(Command\WpCliCommandInterface $wp_cli)
    {
        $this->wp_cli = $wp_cli;
    }

    /**
     * @since [*next-version*]
     *
     * @param string $email_or_login
     *
     * @return int
     */
    public function userId($email_or_login)
    {
        $arguments = array('user', 'get', $email_or_login, '--field=ID');

        try {
            $result = trim($this->wp_cli->run($arguments));

            return (int) $result;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * @since [*next-version*]
     *
     * @param bool $email_or_login
     *
     * @return bool
     */
    public function exists($email_or_login)
    {
        return 0 !== $this->userId($email_or_login);
    }

    /**
     * @since [*next-version*]
     *
     * @link http://wp-cli.org/commands/user/create/
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
    public function create($login, $email, array $attributes = array(), $site_url = '', $graceful = true)
    {
        $login_exists = $this->exists($login);
        if ($login_exists && !$graceful) {
            $user_id = $this->userId($login);
            throw new InvalidArgumentException("User {$login} already exists (ID: {$user_id})");
        } elseif ($login_exists) {
            return $this->userId($login);
        }

        $email_exists = $this->exists($email);
        if ($email_exists && !$graceful) {
            $user_id = $this->userId($login);
            throw new InvalidArgumentException("User {$email} already exists (ID: {$user_id})");
        } elseif ($email_exists) {
            return $this->userId($email);
        }

        $process_arguments = array('user', 'create', $login, $email, '--porcelain');
        $attr_keys         = array(
            'role'         => '--role=',
            'password'     => '--user_pass=',
            'first_name'   => '--first_name=',
            'last_name'    => '--last_name=',
            'display_name' => '--display_name=',
        );
        foreach ($attr_keys as $attribute_key => $process_attribute) {
            if (empty($attributes[ $attribute_key ])) {
                continue;
            }
            $process_arguments[] = $process_attribute . $attributes[ $attribute_key ];
        }

        if (isset($attributes[ 'send_mail' ]) && $attributes[ 'send_mail' ]) {
            $process_arguments[] = '--send_mail';
        }

        if (isset($attributes[ 'registered_at' ]) && is_a($attributes[ 'registered_at' ], 'DateTimeInterface')) {
            /* @var DateTimeInterface $$attributes[ 'registered_at' ] */
            $process_arguments[] = '--user_registered=' . $attributes[ 'registered_at' ]->format('Y-m-d');
        }

        try {
            $result = trim($this->wp_cli->run($process_arguments));

            return (int) $result;
        } catch (Exception $e) {
            if (!$graceful) {
                throw $e;
            }

            return 0;
        }
    }
}
