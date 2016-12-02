<?php
# -*- coding: utf-8 -*-

namespace WpProvision\Api;

interface WpProvisionerInterface
{
    /**
     * @return VersionsInterface
     */
    public function versionList();

    /**
     * @param $wp_dir
     */
    public function setWpDir($wp_dir);
}
