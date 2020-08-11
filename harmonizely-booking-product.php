<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Harmonizely_Booking_Product
 *
 * @wordpress-plugin
 * Plugin Name:       Harmonizely Booking Product
 * Plugin URI:
 * Description:       Creates a Harmonizely appointment booking product type for WooCommerce
 * Version:           1.0.1
 * Author:            Chris Hardie
 * Author URI:        https://chrishardie.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       harmonizely-booking-product
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'HARMONIZELY_BOOKING_PRODUCT_VERSION', '1.0.1' );
define( 'HARMONIZELY_API_BASE', 'https://harmonizely.com/api' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-harmonizely-booking-product-activator.php
 */
function activate_harmonizely_booking_product() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-harmonizely-booking-product-activator.php';
	Harmonizely_Booking_Product_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-harmonizely-booking-product-deactivator.php
 */
function deactivate_harmonizely_booking_product() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-harmonizely-booking-product-deactivator.php';
	Harmonizely_Booking_Product_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_harmonizely_booking_product' );
register_deactivation_hook( __FILE__, 'deactivate_harmonizely_booking_product' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-harmonizely-booking-product.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_harmonizely_booking_product() {

	$plugin = new Harmonizely_Booking_Product();
	$plugin->run();

}
run_harmonizely_booking_product();
