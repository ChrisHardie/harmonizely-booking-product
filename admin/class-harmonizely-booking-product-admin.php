<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Harmonizely_Booking_Product
 * @subpackage Harmonizely_Booking_Product/admin
 * @author     Chris Hardie <chris@chrishardie.com>
 */
class Harmonizely_Booking_Product_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		// Right now, we're not using any admin CSS, so comment this out.
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/harmonizely-booking-product-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		// Right now, we're not using any admin JS, so comment this out.
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/harmonizely-booking-product-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Make sure required dependency plugin(s) are active.
	 */
	public function has_parent_plugin() {

		if (
			( ! function_exists( 'WC' ) )
			&& is_admin()
			&& current_user_can( 'activate_plugins' )
		) {

			add_action(
				'admin_notices',
				function() {
					echo wp_kses_post(
						'<div class="error"><p>'
						. __( 'WooCommerce' )
						. ' '
						. __( 'must be activated to use this plugin. Visit your plugins page to activate.', 'harmonizely-booking-product' )
						. '</p></div>'
					);
				}
			);

			deactivate_plugins( 'harmonizely-booking-product/harmonizely-booking-product.php' );

			// TODO check admin referer
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}

	/**
	 * Add the Harmonizely Bookings settings section to the list of WooCommerce settings sections
	 * @param $sections
	 * @return mixed
	 */
	public function add_settings_section( $sections ) {
		$sections['harmonizely_bookings'] = __( 'Harmonizely Bookings', 'harmonizely-booking-product' );
		return $sections;
	}

	/**
	 * Add the Harmonizely Bookings settings to the settings section created above
	 * @param $settings
	 * @param $current_section
	 * @return array
	 */
	public function add_settings( $settings, $current_section ) {
		// Check the current section is what we want
		if ( 'harmonizely_bookings' === $current_section ) {

			$settings_hbp = array();

			// Add Title to the Settings
			$settings_hbp[] = array(
				'name' => __( 'Harmonizely Bookings', 'harmonizely-booking-product' ),
				'type' => 'title',
				'desc' => __( 'The following options are used to configure Harmonizely Bookings.', 'harmonizely-booking-product' )
					. ' '
					. __( 'To refresh the available meeting types from your account, save this settings page again.', 'harmonizely-booking-product' ),
				'id'   => 'harmonizely_bookings',
			);

			// Add field for API key
			$settings_hbp[] = array(
				'name'     => __( 'Harmonizely API Key', 'harmonizely-booking-product' ),
				'desc_tip' => __( 'The API key generated for your Harmonizely account', 'harmonizely-booking-product' ),
				'id'       => 'hbp_harmonizely_api_key',
				'type'     => 'text',
				'desc'     => __( 'The API key generated for your Harmonizely account', 'harmonizely-booking-product' )
					. '. '
					. __( 'Visit the Integrations page in your Harmonizely account.', 'harmonizely-booking-product' ),
			);

			// End the section
			$settings_hbp[] = array(
				'type' => 'sectionend',
				'id'   => 'harmonizely_bookings',
			);

			return $settings_hbp;

		}

		/**
		 * Return the standard settings
		 **/
		return $settings;
	}

	/**
	 * When the WooCommerce Product settings are saved, check the validity of the Harmonizely API key
	 */
	public function check_harmonizely_api_key() {

		// Retrieve API key and sanitize it
		$harmonizely_api_key = sanitize_text_field( get_option( 'hbp_harmonizely_api_key' ) );

		// If it's empty, remove any option indicating a valid key and then don't do anything else.
		if ( empty( $harmonizely_api_key ) ) {
			delete_option( 'hbp_valid_api_key' );
			delete_transient( 'hbp_api_key_validated' );
			return false;
		}

		// If a transient exists showing we've successfully validated this key recently, don't do it again.
		if ( $harmonizely_api_key === get_transient( 'hbp_api_key_validated' ) ) {
			return false;
		}

		// Make a remote API call to check it
		$harmonizely_validate_key_api_url = HARMONIZELY_API_BASE . '/api-keys/' . $harmonizely_api_key . '/validate';

		$headers = array(
			'Content-Type' => 'application/json',
		);

		$validate_key_api_response = wp_remote_get(
			$harmonizely_validate_key_api_url,
			array(
				'headers' => $headers,
				'method'  => 'GET',
				'timeout' => 75,
			)
		);

		if ( ! is_array( $validate_key_api_response ) || is_wp_error( $validate_key_api_response ) ) {
			$this->harmonizely_api_key_error();
			return false;
		}

		$api_key_response = json_decode( $validate_key_api_response['body'], false );

		// If it's valid, update an option indicating that, and a transient noting we've checked recently
		if ( ! empty( $api_key_response->is_valid ) && ( true === $api_key_response->is_valid ) ) {
			update_option( 'hbp_valid_api_key', true );
			set_transient( 'hbp_api_key_validated', $harmonizely_api_key, DAY_IN_SECONDS );
		} else {
			$this->harmonizely_api_key_error();
			return false;
		}

		return true;
	}

	public function get_harmonizely_meeting_types() {
		// Retrieve API key and sanitize it
		$harmonizely_api_key = sanitize_text_field( get_option( 'hbp_harmonizely_api_key' ) );

		// Make a remote API call to check it
		$harmonizely_meeting_types_api_url = HARMONIZELY_API_BASE . '/users/me/meeting-types';

		$harmonizely_meeting_types_api_url = add_query_arg(
			array(
				'limit'               => apply_filters( 'harmonizely_bookings_max_meeting_types', 50 ),
				'sorting[created_at]' => 'desc',
			),
			$harmonizely_meeting_types_api_url
		);

		$headers = array(
			'Content-Type' => 'application/json',
			'X-API-KEY'    => $harmonizely_api_key,
		);

		$meeting_types_api_response = wp_remote_get(
			esc_url_raw( $harmonizely_meeting_types_api_url ),
			array(
				'headers' => $headers,
				'method'  => 'GET',
				'timeout' => 75,
			)
		);

		if ( ! is_array( $meeting_types_api_response ) || is_wp_error( $meeting_types_api_response ) ) {
			$this->harmonizely_meeting_types_error();
			return false;
		}

		$meeting_types = json_decode( $meeting_types_api_response['body'], false );

		$meeting_types_to_store = array();

		if ( ! empty( $meeting_types->pages ) && ( 1 < $meeting_types->pages ) ) {
			( new WC_Logger() )->log( 'debug', 'More meeting type results are available than were fetched from ' . $harmonizely_meeting_types_api_url );
		}

		if ( ! empty( $meeting_types->_embedded->items ) ) {
			foreach ( $meeting_types->_embedded->items as $meeting_type ) {

				if ( isset( $meeting_type->uuid, $meeting_type->name ) ) {
					$meeting_types_to_store[ sanitize_text_field( $meeting_type->uuid ) ] = sanitize_text_field( $meeting_type->name );
				}
			}

			if ( 0 < count( $meeting_types_to_store ) ) {
				// Save the meeting types in WP options, no autoloading
				update_option( 'hbp_harmonizely_meeting_types', $meeting_types_to_store, false );
			} else {
				$this->harmonizely_meeting_types_error();
				return false;
			}
		} else {
			$this->harmonizely_meeting_types_error();
			return false;
		}

		return true;

	}

	/**
	 * When all plugins have been loaded, load the Harmonizely Booking product class
	 */
	public function register_harmonizely_product_type() {

		// The class responsible for defining the custom WooCommerce product type.
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-harmonizely-booking-product-product.php';

	}

	/**
	 * Add the Harmonizely Bookings WooCommerce product type
	 * @param $types
	 * @return mixed
	 */
	public function add_harmonizely_product_type( $types ) {
		$types['harmonizely_booking'] = __( 'Harmonizely Booking', 'harmonizely-booking-product' );
		return $types;
	}

	/**
	 * Add to and adjust the list of tabs visible for the Harmonizely Bookings WC product type
	 * @param $tabs
	 * @return mixed
	 */
	public function filter_woocommerce_product_tabs( $tabs ) {
		$tabs['harmonizely_booking'] = array(
			'label'  => __( 'Harmonizely Settings', 'harmonizely-booking-product' ),
			'target' => 'harmonizely_booking_options',
			'class'  => ( 'show_if_harmonizely_booking' ),
		);

		$tabs['shipping']['class'][]  = 'hide_if_harmonizely_booking';
		$tabs['attribute']['class'][] = 'hide_if_harmonizely_booking';

		return $tabs;
	}

	/**
	 * Add some simple HTML to create a placeholder DIV in the product options admin
	 */
	public function filter_woocommerce_product_general_options() {
		echo '<div class="options_group show_if_harmonizely_product clear"></div>';
	}

	/**
	 * Use Javascript to make visible the general options tab for our custom product type
	 * It's really silly that this is necessary.
	 */
	public function enable_js_for_woocommerce_product_admin() {
		global $post, $product_object;

		if ( ! $post ) {
			return;
		}

		if ( 'product' !== $post->post_type ) :
			return;
		endif;

		$is_harmonizely_booking = $product_object && 'harmonizely_booking' === $product_object->get_type();

		?>
		<script type='text/javascript'>
			jQuery(document).ready(function () {
				//for Price tab
				jQuery('#general_product_data .pricing').addClass('show_if_harmonizely_booking');

				<?php if ( $is_harmonizely_booking ) { ?>
				jQuery('#general_product_data .pricing').show();
				<?php } ?>
			});
		</script>
		<?php
	}

	/**
	 * Return the HTML to be displayed in the Harmonizely Bookings product add/edit settings
	 */
	public function harmonizely_product_tab_content() {

		$content = '';

		$content .= '<div id="harmonizely_booking_options" class="panel woocommerce_options_panel">';
		$content .= '<div class="options_group">';

		echo $content; // phpcs:ignore

		$meeting_types = get_option( 'hbp_harmonizely_meeting_types' );

		if ( ! empty( $meeting_types ) ) {
			$meeting_types = array_merge(
				array( '' => __( 'Select a meeting type', 'harmonizely-booking-product' ) ),
				$meeting_types
			);

			woocommerce_wp_select(
				array(
					'id'                => '_harmonizely_meeting_type',
					'label'             => __( 'Meeting Type', 'harmonizely-booking-product' ),
					'options'           => $meeting_types,
					'placeholder'       => '',
					'custom_attributes' => array( 'required' => 'required' ),
					'desc_tip'          => 'true',
					'description'       => __( 'Select the Harmonizely Meeting Type that the purchase of this product will grant access to.', 'harmonizely-booking-product' ),
				)
			);
		} else {
			echo '<p>' . __( 'No meeting types were found. Please check your Harmonizely configuration.', 'harmonizely-booking-product' ) . '</p>';
		}

		echo '</div></div>';

	}

	/**
	 * For a post (product) being saved, add the Harmonizely meeting type provided.
	 * @param $post_id
	 * @return bool
	 */
	public function save_harmonizely_product_settings( $post_id ) {

		// check nonce
		if ( ! ( isset( $_POST['woocommerce_meta_nonce'], $_POST['_harmonizely_meeting_type'] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) {
			return false;
		}

		update_post_meta( $post_id, '_harmonizely_meeting_type', sanitize_text_field( $_POST['_harmonizely_meeting_type'] ) );

		return true;
	}

	/**
	 * Enable the Add to Cart button for products using our product type
	 */
	public function harmonizely_product_add_to_cart() {

		global $product;

		$have_valid_harmonizely_api_key = get_option( 'hbp_valid_api_key' );
		$product_meeting_type           = $product->get_meta( '_harmonizely_meeting_type' );

		if ( $have_valid_harmonizely_api_key && ! empty( $product_meeting_type ) ) {
			do_action( 'woocommerce_simple_add_to_cart' );
		}
	}

	/**
	 * Validate the item being added to or updated in the cart
	 * Check number of booking products in the cart, and limit to one.
	 * Make sure the product has an associated meeting type and that we have a valid Harmonizely API key
	 *
	 * @param $validation_passed
	 * @return bool
	 */
	public function validate_cart( $validation_passed ) {

		$max_booking_products = 1;
		$running_quantity     = 0;

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			$product = wc_get_product( $cart_item['product_id'] );

			// See if the product type is ours
			if ( 'harmonizely_booking' === $product->get_type() ) {

				// This doesn't seem to be working right now. Not sure why.
				//				$product_meeting_type = $product->get_meta( '_harmonizely_meeting_type' );
				//
				//				if ( ! $product_meeting_type ) {
				//					wc_add_notice(
				//					/* translators: error message for when product is configured incorrectly and cannot be purchased */
				//						__( 'This product cannot currently be purchased.', 'harmonizely-booking-product' ),
				//						'error'
				//					);
				//
				//					$validation_passed = false;
				//					break;
				//				}

				$running_quantity += (int) $cart_item['quantity'];

				if ( $running_quantity >= $max_booking_products ) {
					wc_add_notice(
						/* translators: error message for maximum quantity of booking product type reached */
						sprintf( esc_html__( 'Only %1$d of this type of product allowed per order.', 'harmonizely-booking-product' ), $max_booking_products ),
						'error'
					);
					// don't add the new product to the cart
					$validation_passed = false;
					// Stop the loop
					break;
				}
			}
		}

		return $validation_passed;

	}

	/**
	 * When an order has been placed that uses one of our product types,
	 * fetch a single-use scheduling URL for the defined meeting type and
	 * add it to the order meta.
	 * @param $order_id
	 * @return bool|void
	 */
	public function add_harmonizely_scheduling_url_to_order( $order_id ) {

		// First make sure the API key stored in options is valid.
		$have_valid_harmonizely_api_key = get_option( 'hbp_valid_api_key' );

		if ( ! $have_valid_harmonizely_api_key ) {
			( new WC_Logger() )->log( 'error', 'No valid Harmonizely API key so abandoning attempt to generate single-use meeting URL for order ' . $order_id );
			return;
		}

		// Get order details from WooCommerce object
		$order = wc_get_order( $order_id );

		// Iterate through order items
		$items = $order->get_items();

		foreach ( $items as $item ) {

			// Store Product ID
			$product_id = $item['product_id'];
			$product    = wc_get_product( $product_id );

			// See if the product type is ours
			if ( 'harmonizely_booking' === $product->get_type() ) {

				// Get the meeting type associated with the product
				$harmonizely_meeting_type = $product->get_meta( '_harmonizely_meeting_type' );

				// Get the Harmonizely API key
				// Retrieve API key and sanitize it
				$harmonizely_api_key = sanitize_text_field( get_option( 'hbp_harmonizely_api_key' ) );

				// If it's empty, don't do anything else. Given validation elsewhere, something is really broken.
				if ( empty( $harmonizely_api_key ) ) {
					$error_message = 'Empty Harmonizely API key encountered when starting to get scheduling URL for order ' . $order_id;
					( new WC_Logger() )->log( 'error', $error_message );
					continue;
				}

				// API URL for fetching single-use scheduling links
				$harmonizely_single_use_api_url = HARMONIZELY_API_BASE . '/single-use-link';

				$headers = array(
					'Content-Type' => 'application/json',
					'X-API-KEY'    => $harmonizely_api_key,
				);

				$body = array(
					'meeting_type_uuid' => $harmonizely_meeting_type,
				);

				$body_json = wp_json_encode( $body );

				// Try to get the single-use scheduling URL
				$single_use_response = wp_remote_post(
					esc_url_raw( $harmonizely_single_use_api_url ),
					array(
						'headers'     => $headers,
						'method'      => 'POST',
						'timeout'     => 75,
						'body'        => $body_json,
						'data_format' => 'body',
					)
				);

				// Check for error response
				if ( ! is_array( $single_use_response ) || is_wp_error( $single_use_response ) ) {
					$order->add_order_note(
						__( 'Error retrieving scheduling URL from Harmonizely', 'harmonizely-booking-product' ),
						0
					);
					$wp_error_message = $single_use_response->get_error_message();
					$error_message    = 'Error response when retrieving scheduling URL from Harmonizely at ' . $harmonizely_single_use_api_url . ' for order ' . $order_id . ': ' . $wp_error_message . ' after POST to ' . $harmonizely_single_use_api_url . ' with body ' . $body_json . ' and headers ' . print_r( $headers, true ); // phpcs:ignore
					( new WC_Logger() )->log( 'error', $error_message );

					return false;
				}

				// If no general error, decode the JSON response content
				$meeting = json_decode( $single_use_response['body'], false );

				// Assuming we got a valid meeting URL, add it to the order and add an internal note.
				if ( ! empty( $meeting->url ) ) {
					// API Response Stored as Post Meta
					update_post_meta( $order_id, '_harmonizely_scheduling_url', $meeting->url );
					$order->add_order_note(
						__( 'Scheduling URL from Harmonizely', 'harmonizely-booking-product' )
						. ': '
						. $meeting->url,
						0
					);
				} else {
					// Otherwise, add an internal order note indicating a problem, and log as an error.
					$order->add_order_note(
						__( 'Error retrieving scheduling URL from Harmonizely', 'harmonizely-booking-product' ),
						0
					);

					$error_message = 'No single-use scheduling URL returned for event type ' . $harmonizely_meeting_type . ' for order ' . $order_id . ': ' . print_r( $meeting, true ) . ' after POST to ' . $harmonizely_single_use_api_url . ' with body ' . $body_json . ' and headers ' . print_r( $headers, true ); // phpcs:ignore
					( new WC_Logger() )->log( 'error', $error_message );

					return false;

				}
			}
		}
	}

	/**
	 * Add a WooCommerce order action to regenerate the scheduling URL for any Harmonizely booking items
	 * @param $actions
	 * @return mixed
	 */
	public function add_regenerate_scheduling_url_order_action( $actions ) {
		global $theorder;

		$items = $theorder->get_items();

		$has_harmonizely_item = false;

		foreach ( $items as $item ) {
			$product = wc_get_product( $item['product_id'] );

			// See if the product type is ours
			if ( 'harmonizely_booking' === $product->get_type() ) {
				$has_harmonizely_item = true;
			}
		}

		if ( $has_harmonizely_item ) {
			$actions['hbp_generate_url'] = __( 'Regenerate Harmonizely scheduling link', 'harmonizely-booking-product' );
		}

		return $actions;
	}

	/**
	 * Handle the order action for regenerating the Harmonizely scheduling URL
	 * @param WC_Order $order
	 */
	public function regenerate_scheduling_url( $order ) {

		$order_id = $order->get_id();

		delete_post_meta( $order_id, '_harmonizely_scheduling_url' );
		$this->add_harmonizely_scheduling_url_to_order( $order_id );

	}

	/**
	 * Add information about the meeting scheduling link to the new order confirmation email.
	 * @param $order
	 * @param $admin
	 * @param $plain
	 * @param $email
	 */
	public function add_scheduling_info_to_order_email( $order, $admin, $plain, $email ) {
		$status = $order->get_status();

		// If the order is processing (payment made but awaiting fulfillment) or complete, proceed.
		if ( in_array( $status, array( 'completed', 'processing' ), true ) ) {

			// Get the scheduling URL from order meta
			$harmonizely_scheduling_url = $order->get_meta( '_harmonizely_scheduling_url' );

			if ( ! empty( $harmonizely_scheduling_url ) ) {

				if ( $plain ) {
					$output = sprintf(
						'*%s*: %s %s: %s',
						esc_html__( 'Schedule an appointment', 'harmonizely-booking-product' ),
						esc_html__( 'This order grants scheduling access.', 'harmonizely-booking-product' ),
						esc_html__( 'Schedule Now', 'harmonizely-booking-product' ),
						esc_url_raw( $harmonizely_scheduling_url )
					);
				} else {
					$output = sprintf(
						'<p class="harmonizely-booking-notice"><strong>%s</strong><br />%s <a href="%s" class="harmonizely-booking-link">%s</a></p>',
						esc_html__( 'Schedule an appointment', 'harmonizely-booking-product' ),
						esc_html__( 'This order grants scheduling access.', 'harmonizely-booking-product' ),
						esc_url_raw( $harmonizely_scheduling_url ),
						esc_html__( 'Schedule Now', 'harmonizely-booking-product' )
					);
				}

				// Return the output after filtering
				echo wp_kses_post( apply_filters( 'harmonizely_bookings_order_email_notice', $output, $order, $plain, $harmonizely_scheduling_url ) );

			} else {
				$warning_message = 'No scheduling URL found in order meta when trying to add to email, for order ' . $order->get_id();
				( new WC_Logger() )->log( 'warning', $warning_message );
			}
		}
	}

	/**
	 * A helper function.
	 * If our Harmonizely API key is not valid, delete the related option and transient, and add a custom WC admin notice
	 */
	private function harmonizely_api_key_error() {
		delete_option( 'hbp_valid_api_key' );
		delete_transient( 'hbp_api_key_validated' );

		$notice_html = '<strong>' . __( 'The configured Harmonizely API key is not valid.', 'harmonizely-booking-product' ) . '</strong>';
		WC_Admin_Notices::add_custom_notice( 'hbp_invalid_api_key', $notice_html );
	}

	/**
	 * A helper function.
	 * If our Harmonizely meeting types don't exist or are not valid, delete the related option and add a custom WC admin notice
	 */
	private function harmonizely_meeting_types_error() {
		delete_option( 'hbp_harmonizely_meeting_types' );

		$notice_html = '<strong>' . __( 'No valid meeting types available from the Harmonizely API.', 'harmonizely-booking-product' ) . '</strong>';
		WC_Admin_Notices::add_custom_notice( 'hbp_invalid_meeting_types', $notice_html );
	}

}
