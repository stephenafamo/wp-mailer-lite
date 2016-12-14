<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://stephenafamo.com
 * @since      1.0.0
 *
 * @package    Wp_Mailer_Lite
 * @subpackage Wp_Mailer_Lite/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Mailer_Lite
 * @subpackage Wp_Mailer_Lite/includes
 * @author     Stephen Afam-Osemene <stephenafamo@gmail.com>
 */
class Wp_Mailer_Lite_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-mailer-lite',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
