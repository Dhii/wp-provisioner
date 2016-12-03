<?php

namespace Dhii\WpProvision\Wp;

/**
 * Something that can act as a wrapper for WP theme commands.
 *
 * @since [*next-version*]
 */
interface ThemeInterface
{
    const K_NAME    = 'name';
    const K_STATUS  = 'status';
    const K_VERSION = 'version';
    const K_AUTHOR  = 'author';
    const K_SLUG    = 'slug';

    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_UNKNOWN  = 'unknown';

    /**
     * Provides access to the `activate` subcommand.
     *
     * @since [*next-version*]
     *
     * @param string   $theme   The slug of the theme to activate.
     * @param string[] $options Additional options.
     */
    public function activate($theme, $options = []);

    /**
     * Provides access to the `delete` subcommand.
     *
     * @since [*next-version*]
     *
     * @param string   $theme   The slug of the theme to delete.
     * @param string[] $options Additional options.
     */
    public function delete($theme, $options = []);

    /**
     * Provides access to the `install` subcommand.
     *
     * @param string   $theme   A theme slug, the path to a local zip file, or URL to a remote zip file.
     * @param string[] $options Additional options.
     *
     * @since [*next-version*]
     */
    public function install($theme, $options = []);

    /**
     * Provides access to the `is-installed` subcommand.
     *
     * @since [*next-version*]
     *
     * @param string   $theme   The slug of the theme to check.
     * @param string[] $options Additional options.
     *
     * @return bool True if the specified theme is installed; false otherwise.
     */
    public function isInstalled($theme, $options = []);

    /**
     * Provides access to the `list` subcommand.
     *
     * @since [*next-version*]
     *
     * @return array[][] An array of theme data arrays.
     */
    public function getAll($options = []);

    /**
     * Provides access to the `status` subcommand.
     *
     * @since [*next-version*]
     *
     * @param string   $theme   The slug of the theme to check.
     * @param string[] $options Additional options.
     *
     * @return string[][] An array of arrays (by theme slug) with status keys and values.
     *  If $theme is passed, the keys will be K_NAME, K_STATUS, K_VERSION, K_AUTHOR, K_SLUG.
     *  If $theme is not passed, the keys will only be K_SLUG, K_STATUS, K_VERSION.
     */
    public function getStatus($theme = null, $options = []);

    /**
     * Provides access to the `get` subcommand.
     *
     * @since [*next-version*]
     *
     * @param string   $theme   The slug of the theme to check.
     * @param string[] $options Additional options.
     *
     * @return string[] An array of status keys and values.
     */
    public function getDetails($theme, $options = []);

    /**
     * Provides access to the `update` subcommand.
     *
     * @since [*next-version*]
     *
     * @param string   $theme   A slug or an array of theme slugs to update.
     *                          In certain cases may be empty.
     * @param string[] $options Additional options.
     */
    public function update($theme, $options = []);
}
