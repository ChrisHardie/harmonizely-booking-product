<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/includes
 * @author     Chris Hardie <chris@chrishardie.com>
 */
class Harmonizely_Booking_Product_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Add the Harmonizely Booking product type to the WC-managed product_type taxonomy
		if ( ! get_term_by( 'slug', 'harmonizely_booking', 'product_type' ) ) {
			wp_insert_term( 'harmonizely_booking', 'product_type' );
		}
	}
}
