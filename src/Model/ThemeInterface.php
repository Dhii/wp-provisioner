<?php

namespace Dhii\WpProvision\Model;

/**
 * Something that can represent a theme.
 *
 * @since [*next-version*]
 */
interface ThemeInterface
{
    const K_SLUG    = 'slug';
    const K_NAME    = 'name';
    const K_STATUS  = 'status';
    const K_VERSION = 'version';
    const K_UPDATES = 'updates';
    const K_AUTHOR  = 'author';

    const STATUS_ACTIVE      = 'active';
    const STATUS_INACTIVE    = 'inactive';
    const STATUS_UNKNOWN     = 'unknown';
    const STATUS_UNINSTALLED = 'uninstalled';
    const STATUS_UPDATE      = 'update';

    const UPDATE_AVAILABLE   = 'available';
    const UPDATE_UNAVAILABLE = 'unavailable';

    /**
     * Retrieves the slug of the theme.
     *
     * @since [*next-version*]
     *
     * @return string The theme slug.
     */
    public function getSlug();

    /**
     * Retrieve the theme status.
     *
     * @since [*next-version*]
     *
     * @return string The theme status. One of the STATUS_* constants - see class constants.
     */
    public function getStatus();

    /**
     * Retrieves the name of the theme.
     *
     * @since [*next-version*]
     *
     * @return string|null The theme name.
     */
    public function getName();

    /**
     * Retrieves the version of the theme.
     *
     * @since [*next-version*]
     *
     * @return string The theme version.
     */
    public function getVersion();

    /**
     * Retrieves the update status code of the theme.
     *
     * @since [*next-version*]
     *
     * @return string|null The theme update status code.
     */
    public function getUpdateStatus();

    /**
     * Determines whether an upate is available for the theme.
     *
     * @since [*next-version*]
     *
     * @return bool True if an update is available for this theme; false otherwise.
     */
    public function isUpdateAvailable();

    /**
     * Retrieves the author info of the theme.
     *
     * @since [*next-version*]
     *
     * @return string|null The author info.
     */
    public function getAuthor();

    /**
     * Determines whether the theme is active.
     *
     * @since [*next-version*]
     *
     * @return bool True if the theme is active; false otherwise.
     */
    public function isActive();

    /**
     * Determine whether the theme is installed.
     *
     * @since [*next-version*]
     *
     * @return bool True if the theme is installed; false otherwise.
     */
    public function isInstalled();
}
