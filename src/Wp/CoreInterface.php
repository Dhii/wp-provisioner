<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Wp;

/**
 * Interface Core.
 *
 * @since [*next-version*]
 */
interface CoreInterface
{
    /**
     * @since [*next-version*]
     *
     * @param bool $network If multisite is installed
     *
     * @return bool
     */
    public function isInstalled($network = false);

    /**
     * @since [*next-version*]
     *
     * @param string $url      URL of the new site
     * @param array  $admin
     *                         string $admin[ 'email' ] (required)
     *                         string $admin[ 'login' ] (required)
     *                         string $admin[ 'password' ] (optional, will be generated if not provided)
     * @param array  $options
     *                         string $options[ 'title' ]
     *                         bool   $options[ 'skip_email' ] Skip the information email, default: FALSE
     * @param bool   $graceful Throw exceptions, when set to FALSE, default: TRUE
     *
     * @return bool
     */
    public function install($url, array $admin, array $options = array(), $graceful = true);

    /**
     * @since [*next-version*]
     *
     * @param array $options
     *                       string $options[ 'base_path' ] Base URL path for all sites, default: '/'
     *                       string $options[ 'title' ] Title of the network
     *                       bool   $options[ 'subdomains' ] Subdomain install? Default: TRUE
     *
     * @return bool
     */
    public function multisiteConvert(array $options = array());

    /**
     * @since [*next-version*]
     *
     * @param string $url      The URL of the network (e.g. http://example.dev/)
     * @param array  $admin
     *                         string $admin[ 'email' ] (required)
     *                         string $admin[ 'login' ] (required)
     *                         string $admin[ 'password' ] (optional, will be generated if not provided)
     * @param array  $options
     *                         string $options[ 'title' ]
     *                         bool   $options[ 'skip_email' ] Skip the information email, default: FALSE
     *                         bool   $options[ 'subdomains' ] Subdomain install, default: TRUE
     * @param bool   $graceful Throw exceptions, when set to FALSE, default: TRUE
     *
     * @return bool
     */
    public function multisiteInstall($url, array $admin, array $options = array(), $graceful = true);
}
