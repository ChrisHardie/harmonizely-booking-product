<?php

/**
 * Register the custom WooCommerce product type used by the plugin.
 *
 * @since      1.0.0
 *
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/includes
 */

// Make sure WooCommerce's product classes are available first
if ( ! class_exists( 'WC_Product_Simple' ) ) {
	return;
}

class WC_Product_Harmonizely_Booking extends WC_Product_Simple {

	public function __construct( $product ) {
		$this->product_type = 'harmonizely_booking'; // name of your custom product type
		parent::__construct( $product );
	}

	public function get_type() {
		return 'harmonizely_booking';
	}

	public function is_virtual() {
		return true;
	}

	public function is_purchasable() {
		return true;
	}
}
