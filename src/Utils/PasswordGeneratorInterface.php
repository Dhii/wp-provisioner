<?php # -*- coding: utf-8 -*-

namespace WpProvision\Utils;

/**
 * Interface PasswordGenerator
 *
 * @package WpProvision\Utils
 */
interface PasswordGeneratorInterface {

	/**
	 * @return string
	 */
	public function generatePassword();
}
