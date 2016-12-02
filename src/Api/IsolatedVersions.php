<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Api;

use LogicException;

/**
 * Class IsolatedVersions.
 *
 * Minimal implementation of the version list. This does not resolve consecutive version numbers (as planned
 * in the API concept)
 *
 * @since [*next-version*]
 */
class IsolatedVersions implements VersionsInterface
{
    /**
     * @var array
     */
    private $versions = [];

    /**
     * @var WpCommandProviderInterface
     */
    private $provider;

    /**
     * @since [*next-version*]
     *
     * @param WpCommandProviderInterface $provider
     */
    public function __construct(WpCommandProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @since [*next-version*]
     *
     * @param string $version
     *
     * @return bool
     */
    public function versionExists($version)
    {
        return isset($this->versions[ $version ]);
    }

    /**
     * @since [*next-version*]
     *
     * @param string $version
     * @param bool   $isolation
     *
     * @return bool
     */
    public function executeProvision($version, $isolation = false)
    {
        if (!$this->versionExists($version)) {
            throw new LogicException("No provisioner registered for version '{$version}''");
        }

        call_user_func_array($this->versions[ $version ], [$this->provider]);
    }

    /**
     * Register a provision routine.
     *
     * @since [*next-version*]
     *
     * @param string   $version
     * @param callable $callback
     * @param bool     $isolation
     *
     * @return bool
     */
    public function addProvision($version, callable $callback, $isolation = false)
    {
        if ($this->versionExists($version)) {
            throw new LogicException("Version '{$version}' already exists");
        }

        $this->versions[ $version ] = $callback;
    }
}
