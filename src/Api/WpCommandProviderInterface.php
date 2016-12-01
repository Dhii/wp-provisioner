<?php # -*- coding: utf-8 -*-

namespace WpProvision\Api;

use
	WpProvision\Wp;

/**
 * Interface WpCommandProvider
 *
 * @package WpProvision\Api
 */
interface WpCommandProviderInterface {

	/**
	 * @return Wp\CoreInterface
	 */
	public function core();

	/**
	 * @return Wp\PluginInterface
	 */
	public function plugin();

	/**
	 * @return Wp\SiteInterface
	 */
	public function site();

	/**
	 * @return Wp\UserInterface
	 */
	public function user();
}
