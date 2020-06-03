<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/includes
 * @author     Chris Hardie <chris@chrishardie.com>
 */
class Harmonizely_Booking_Product {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Harmonizely_Booking_Product_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'HARMONIZELY_BOOKING_PRODUCT_VERSION' ) ) {
			$this->version = HARMONIZELY_BOOKING_PRODUCT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'harmonizely-booking-product';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Harmonizely_Booking_Product_Loader. Orchestrates the hooks of the plugin.
	 * - Harmonizely_Booking_Product_i18n. Defines internationalization functionality.
	 * - Harmonizely_Booking_Product_Admin. Defines all hooks for the admin area.
	 * - Harmonizely_Booking_Product_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-harmonizely-booking-product-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-harmonizely-booking-product-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-harmonizely-booking-product-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-harmonizely-booking-product-public.php';

		$this->loader = new Harmonizely_Booking_Product_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Harmonizely_Booking_Product_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Harmonizely_Booking_Product_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Harmonizely_Booking_Product_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'has_parent_plugin' );
		$this->loader->add_filter( 'woocommerce_get_sections_products', $plugin_admin, 'add_settings_section' );
		$this->loader->add_filter( 'woocommerce_get_settings_products', $plugin_admin, 'add_settings', 10, 2 );
		$this->loader->add_action( 'woocommerce_update_options_products', $plugin_admin, 'check_harmonizely_api_key' );
		$this->loader->add_action( 'woocommerce_update_options_products', $plugin_admin, 'get_harmonizely_meeting_types' );

		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'register_harmonizely_product_type' );
		$this->loader->add_filter( 'product_type_selector', $plugin_admin, 'add_harmonizely_product_type' );
		$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'filter_woocommerce_product_tabs' );
		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'harmonizely_product_tab_content' );
		$this->loader->add_action( 'woocommerce_product_options_general_product_data', $plugin_admin, 'filter_woocommerce_product_general_options' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'enable_js_for_woocommerce_product_admin' );
		$this->loader->add_action( 'woocommerce_process_product_meta_harmonizely_booking', $plugin_admin, 'save_harmonizely_product_settings' );
		$this->loader->add_action( 'woocommerce_harmonizely_booking_add_to_cart', $plugin_admin, 'harmonizely_product_add_to_cart' );
		$this->loader->add_filter( 'woocommerce_add_to_cart_validation', $plugin_admin, 'validate_cart', 10, 1 );
		$this->loader->add_filter( 'woocommerce_update_cart_validation', $plugin_admin, 'validate_cart', 10, 1 );
		$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_admin, 'add_harmonizely_scheduling_url_to_order' );
		$this->loader->add_action( 'woocommerce_email_order_details', $plugin_admin, 'add_scheduling_info_to_order_email', 1, 4 );
		$this->loader->add_action( 'woocommerce_order_actions', $plugin_admin, 'add_regenerate_scheduling_url_order_action' );
		$this->loader->add_action( 'woocommerce_order_action_hbp_generate_url', $plugin_admin, 'regenerate_scheduling_url' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Harmonizely_Booking_Product_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Harmonizely_Booking_Product_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
