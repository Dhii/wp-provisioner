<?php
# -*- coding: utf-8 -*-

namespace Dhii\WpProvision\Wp;

use Dhii\WpProvision\Command;
use InvalidArgumentException;
use Exception;

/**
 * Class WpCliPlugin.
 *
 * @since [*next-version*]
 */
class WpCliPlugin implements PluginInterface
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
     * @link http://wp-cli.org/commands/plugin/activate/
     *
     * @param string|array $plugin  The plugin slug or a list of slugs (e.g. 'multilingual-press', 'akismet' )
     * @param array        $options
     *                              bool   $options[ 'network' ] If set to TRUE, the plugin gets activated networkwide, default: FALSE
     *                              bool   $options[ 'all' ] If set to TRUE, all plugins gets activated (regardless of $plugin parameter)
     *                              string $option[ 'site_url' ] The site_url the plugin should be activated in, default: network main site
     *
     * @return bool
     */
    public function activate($plugin, array $options = array())
    {
        if (!is_array($plugin) && !is_string($plugin)) {
            throw new InvalidArgumentException('First argument $plugin must be of type string or array');
        }
        $arguments = array('plugin', 'activate');
        if (isset($options[ 'all' ]) && true === $options[ 'all' ]) {
            $arguments[] = '--all';
        } else {
            if (is_string($plugin)) {
                $arguments[] = $plugin;
            } else {
                $arguments = array_merge($arguments, $plugin);
            }
        }

        if (isset($options[ 'network' ]) && true === $options[ 'network' ]) {
            $arguments[] = '--network';
        }

        if (isset($options[ 'site_url' ])) {
            $arguments[] = "--url={$options[ 'site_url' ]}";
        }

        $this->wp_cli->run($arguments);

        return true;
    }

    /**
     * @since [*next-version*]
     *
     * Deactivates plugins. Note that plugins only gets deactivated when the plugins activation status matches the
     * given $option[ 'network' ] parameter.
     * If in doubt, check $this->isActive( 'plugin' ) and $this->isActive( 'plugin', ['network' => TRUE ] ) for
     * the plugins status.
     *
     * @link http://wp-cli.org/commands/plugin/deactivate/
     *
     * @param string|array $plugin  The plugin slug or a list of slugs (e.g. 'multilingual-press', 'akismet' )
     * @param array        $options
     *                              bool   $options[ 'network' ] If set to TRUE, the plugin gets activated network wide, default: FALSE
     *                              bool   $options[ 'all' ] If set to TRUE, all plugins gets activated (regardless of $plugin parameter), default: FALSE
     *                              bool   $options[ 'uninstall' ] If set to TRUE, the plugin gets uninstalled after deactivation, default: FALSE
     *                              string $option[ 'site_url' ] The site_url the plugin should be deactivated, default: network main site
     *
     * @return bool
     */
    public function deactivate($plugin, array $options = array())
    {
        if (!is_array($plugin) && !is_string($plugin)) {
            throw new InvalidArgumentException('First argument $plugin must be of type string or array');
        }
        $arguments = array('plugin', 'deactivate');
        if (isset($options[ 'all' ]) && true === $options[ 'all' ]) {
            $arguments[] = '--all';
        } else {
            if (is_string($plugin)) {
                $arguments[] = $plugin;
            } else {
                $arguments = array_merge($arguments, $plugin);
            }
        }

        if (isset($options[ 'network' ]) && true === $options[ 'network' ]) {
            $arguments[] = '--network';
        }

        if (isset($options[ 'uninstall' ]) && true == $options[ 'uninstall' ]) {
            $arguments[] = '--uninstall';
        }

        if (isset($options[ 'site_url' ])) {
            $arguments[] = "--url={$options[ 'site_url' ]}";
        }

        $this->wp_cli->run($arguments);

        return true;
    }

    /**
     * @since [*next-version*]
     *
     * Is installed means that the plugin is available for activation.
     *
     * @link http://wp-cli.org/commands/plugin/is-installed/
     *
     * @param string $plugin  The plugin slug (e.g. 'multilingual-press', 'akismet' )
     * @param array  $options (No options supported at the moment)
     *
     * @return bool
     */
    public function isInstalled($plugin, array $options = array())
    {
        if (!is_string($plugin)) {
            throw new InvalidArgumentException('First parameter $plugin must be of type string');
        }
        $arguments = array('plugin', 'is-installed', $plugin);

        try {
            $this->wp_cli->run($arguments);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @since [*next-version*]
     *
     * Check if a plugin is active. Method will return FALSE if a plugin is active for network but the parameter
     * $option[ 'network' ] is omitted or set to 'FALSE'.
     *
     * @param string $plugin  The plugin slug (e.g. 'multilingual-press', 'akismet' )
     * @param array  $options
     *                        bool   $options[ 'network' ]  Check if the plugin is activated network wide, default: FALSE
     *                        string $options[ 'site_url' ] The URL of the site to check (default: the network main site, unused in single-site installs)
     *
     * @return bool
     */
    public function isActive($plugin, array $options = array())
    {
        if (!is_string($plugin)) {
            throw new InvalidArgumentException('First parameter $plugin must be of type string');
        }
        $arguments = array('plugin', 'list', "--name={$plugin}", '--field=status');
        if (isset($options[ 'site_url' ])) {
            $arguments[] = "--url={$options[ 'site_url' ]}";
        }

        $result = trim($this->wp_cli->run($arguments));
        if (isset($options[ 'network' ]) && true === $options[ 'network' ] && 'active-network' === $result) {
            return true;
        }

        return 'active' === $result;
    }

    /**
     * @since [*next-version*]
     *
     * Run plugin uninstall hooks and remove plugin files (if $option[ 'delete' ] is set to TRUE).
     * Note: WP-CLI seems to have an issue, when a plugin is activated network-wide. To be save, you should check
     * the plugins status and deactivate it explicitly before.
     *
     * Todo: Find out what that issue is
     *
     * @link http://wp-cli.org/commands/plugin/uninstall/
     *
     * @param string|array $plugin  The plugin slug or a list of slugs (e.g. 'multilingual-press', 'akismet' )
     * @param array        $options
     *                              bool   $options[ 'deactivate' ] Deactivate plugin before uninstallation, default: TRUE
     *                              bool   $option[ 'delete' ] Deletes files after uninstallation , default: FALSE
     *                              string $option[ 'site_url' ] The site_url the uninstall routines should run in, default: network main site
     *
     * @return bool
     */
    public function uninstall($plugin, array $options = array())
    {
        if (!is_array($plugin) && !is_string($plugin)) {
            throw new InvalidArgumentException('First argument $plugin must be of type string or array');
        }

        $arguments = array('plugin', 'uninstall');
        if (is_string($plugin)) {
            $arguments[] = $plugin;
        } else {
            $arguments = array_merge($arguments, $plugin);
        }

        if (!isset($options[ 'deactivate']) || false !== $options[ 'deactivate' ]) {
            $arguments[] = '--deactivate';
        }

        if (!isset($options[ 'delete' ]) || true !== $options[ 'delete' ]) {
            $arguments[] = '--skip-delete';
        }

        if (isset($options[ 'site_url' ])) {
            $arguments[] = "--url={$options[ 'site_url' ]}";
        }

        echo $this->wp_cli->run($arguments);

        return true;
    }
}
