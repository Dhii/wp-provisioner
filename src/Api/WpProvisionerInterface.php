<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Api;

/**
 * @since [*next-version*]
 */
interface WpProvisionerInterface
{
    /**
     * @since [*next-version*]
     *
     * @return VersionsInterface
     */
    public function versionList();

    /**
     * @since [*next-version*]
     *
     * @param $wp_dir
     */
    public function setWpDir($wp_dir);
}
