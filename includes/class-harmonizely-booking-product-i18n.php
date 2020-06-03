<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/includes
 * @author     Chris Hardie <chris@chrishardie.com>
 */
class Harmonizely_Booking_Product_i18n {


	/**
	 * Load the plugin text domain for translation.
	 * Not technically required once the plugin is on wporg directory.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'harmonizely-booking-product',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
