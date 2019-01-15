<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link        https://wordpress.org/plugins/ferret
 * @since      1.0.0
 *
 * @package    Ferret
 * @subpackage Ferret/includes
 */

/**
 * Define the internationalization functionality.
 *
 * @since      1.0.0
 * @package    Ferret
 * @subpackage Ferret/includes
 * @author     Aaron Arney <aarney@leapsparkagency.com>
 */
class Ferret_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'wordpress-sentry',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
