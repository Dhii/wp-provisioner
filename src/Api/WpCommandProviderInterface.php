<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Api;

use WpProvision\Wp;

/**
 * Interface WpCommandProvider.
 *
 * @since [*next-version*]
 */
interface WpCommandProviderInterface
{
    /**
     * @since [*next-version*]
     *
     * @return Wp\CoreInterface
     */
    public function core();

    /**
     * @since [*next-version*]
     *
     * @return Wp\PluginInterface
     */
    public function plugin();

    /**
     * @since [*next-version*]
     *
     * @return Wp\SiteInterface
     */
    public function site();

    /**
     * @since [*next-version*]
     *
     * @return Wp\UserInterface
     */
    public function user();
}
