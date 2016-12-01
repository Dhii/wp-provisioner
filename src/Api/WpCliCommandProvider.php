<?php # -*- coding: utf-8 -*-

namespace WpProvision\Api;

use 
	WpProvision\Command,
	WpProvision\Utils,
	WpProvision\Wp;

/**
 * Class WpCliCommandProvider
 *
 * Temporary solution to quickly instantiate Wp-Command instances. It's in fact a 
 * courier anti-pattern and will be replaced by a IOC container
 * 
 * @package WpProvision\Api
 */
class WpCliCommandProvider implements WpCommandProviderInterface {

	/**
	 * @var Command\WpCliCommandInterface
	 */
	private $wp_cli;
	
	private $core;
	private $plugin;
	private $site;
	private $user;
	
	public function __construct( Command\WpCliCommandInterface $wp_cli ) {
	
		$this->wp_cli = $wp_cli;
		$this->core = new Wp\WpCliCore( $wp_cli, new Utils\Sha1PasswordGenerator );
		$this->plugin = new Wp\WpCliPlugin( $wp_cli );
		$this->user = new Wp\WpCliUser( $wp_cli );
		$this->site = new Wp\WpCliSite( $wp_cli, $this->user, $this->plugin );
	}

	/**
	 * @return Wp\CoreInterface
	 */
	public function core() {
		
		return $this->core;
	}

	/**
	 * @return Wp\PluginInterface
	 */
	public function plugin() {
		
		return $this->plugin;
	}

	/**
	 * @return Wp\SiteInterface
	 */
	public function site() {
		
		return $this->site;
	}

	/**
	 * @return Wp\UserInterface
	 */
	public function user() {
		
		return $this->user;
	}

}
