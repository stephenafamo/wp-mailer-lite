<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://stephenafamo.com
 * @since             1.0.0
 * @package           Wp_Mailer_Lite
 *
 * @wordpress-plugin
 * Plugin Name:       WP Mailer Lite
 * Plugin URI:        https://github.com/stephenafamo/wp-mailer-lite
 * Description:       Adds all wordpress users to a specified mailer lite group.
 * Version:           1.0.0
 * Author:            Stephen Afam-Osemene
 * Author URI:        https://stephenafamo.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-mailer-lite
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-mailer-lite-activator.php
 */
function activate_wp_mailer_lite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mailer-lite-activator.php';
	Wp_Mailer_Lite_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-mailer-lite-deactivator.php
 */
function deactivate_wp_mailer_lite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mailer-lite-deactivator.php';
	Wp_Mailer_Lite_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_mailer_lite' );
register_deactivation_hook( __FILE__, 'deactivate_wp_mailer_lite' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-mailer-lite.php';
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_mailer_lite() {

	$plugin = new Wp_Mailer_Lite();
	$plugin->run();

}
run_wp_mailer_lite();
