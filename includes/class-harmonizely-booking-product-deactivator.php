<?php

/**
 * Fired during plugin deactivation
 *
 * @since      1.0.0
 *
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/includes
 * @author     Chris Hardie <chris@chrishardie.com>
 */
class Harmonizely_Booking_Product_Deactivator {

	/**
	 * Clean up some options and transients.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( 'hbp_harmonizely_api_key' );
		delete_option( 'hbp_valid_api_key' );
		delete_transient( 'hbp_api_key_validated' );
		delete_option( 'hbp_harmonizely_meeting_types' );
	}

}
