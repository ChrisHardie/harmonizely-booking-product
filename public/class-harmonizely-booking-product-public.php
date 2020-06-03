<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/public
 * @author     Chris Hardie <chris@chrishardie.com>
 */
class Harmonizely_Booking_Product_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Harmonizely_Booking_Product_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Harmonizely_Booking_Product_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Right now, we're not using any public CSS, so comment this out.
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/harmonizely-booking-product-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Harmonizely_Booking_Product_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Harmonizely_Booking_Product_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Right now, we're not using any public JS, so comment this out.
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/harmonizely-booking-product-public.js', array( 'jquery' ), $this->version, false );

	}

}
