<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Wp;

use Exception;

/**
 * Interface Site.
 *
 * @since [*next-version*]
 */
interface SiteInterface
{
    /**
     * @since [*next-version*]
     *
     * @param string $url        The site url (e.g. example.dev/en/)
     * @param int    $network_id
     *
     * @return bool
     */
    public function exists($url, $network_id = 0);

    /**
     * @since [*next-version*]
     *
     * @param string $url        The site url (e.g. example.dev/en/)
     * @param int    $network_id
     *
     * @return int
     */
    public function siteId($url, $network_id = 0);

    /**
     * @since [*next-version*]
     *
     * @param string $url        (Site URL including protocol, e.g. https://whatever.mysite.tld/en/ )
     * @param array  $attributes
     *                           string $attributes[ 'user_email' ]
     *                           string $attributes[ 'title' ]
     *                           bool $attributes[ 'private' ]
     *                           string $attributes[ 'slug' ] (Ignores the URL parameter and just create the site with this slug)
     * @param int    $network_id
     * @param bool   $graceful   Set to FALSE to throw exceptions if anything goes wrong
     *
     * @throws Exception
     *
     * @return int
     */
    public function create($url, array $attributes = [], $network_id = 0, $graceful = true);
}
