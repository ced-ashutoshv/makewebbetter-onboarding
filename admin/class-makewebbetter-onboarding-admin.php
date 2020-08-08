<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Makewebbetter_Onboarding
 * @subpackage Makewebbetter_Onboarding/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Makewebbetter_Onboarding
 * @subpackage Makewebbetter_Onboarding/admin
 * @author     Make Web Better <dev@mwb.com>
 */
class Makewebbetter_Onboarding_Admin {

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
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Makewebbetter_Onboarding_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Makewebbetter_Onboarding_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( $this->is_valid_page_screen() ) {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/makewebbetter-onboarding-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Makewebbetter_Onboarding_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Makewebbetter_Onboarding_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( $this->is_valid_page_screen() ) {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/makewebbetter-onboarding-admin.js', array( 'jquery' ), $this->version, false );
		}
	}

	/**
	 * Get all active plugins by MakeWebBetter.
	 *
	 * @since    1.0.0
	 * Help : replace/define CURRENT_PLUGIN_FILE with plugin-folder/plugin-file.php
	 */
	public function check_mwb_active_plugins() {

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$all_plugins = get_plugins();
		if ( ! empty( $all_plugins ) && is_array( $all_plugins ) ) {
			foreach ( $all_plugins as $plugin => $plugin_data ) {	

				if ( is_plugin_active( $plugin ) ) {

					// Only active plugins shall be checked.
					if ( CURRENT_PLUGIN_FILE !== $plugin ) {

						if ( ! defined( 'MAKEWEBBETTER_PLUGIN_EXISTS' ) && ( strpos( $plugin_data[ 'PluginURI' ], 'makewebbetter.com' )  !== false || strpos( $plugin_data[ 'AuthorURI' ], 'makewebbetter.com' ) !== false ) ) {

							// Lets save the active plugins data, can be used afterwards for validation.
							$active_mwb_plugins = get_option( '_mwb_active_plugins', array() );
							array_push( $active_mwb_plugins, $plugin_data );
							update_option(  '_mwb_active_plugins', $active_mwb_plugins );
						}
					}
				}
			}
		}
	}

	/**
	 * Get all valid screens to add scripts and templates.
	 *
	 * @since    1.0.0
	 * Help : replace CURRENT_PLUGIN_FILE with plugin-folder/plugin-file.php
	 */
	public function add_mwb_frontend_screens( $valid_screens=array() ) {

		if ( is_array( $valid_screens ) ) {
			
			// Push your screen here.
			array_push( $valid_screens, 'toplevel_page_elementor' );
		}
		return $valid_screens;
	}

	/**
	 * Get all valid screens to add scripts and templates.
	 *
	 * @since    1.0.0
	 * Help : replace CURRENT_PLUGIN_FILE with plugin-folder/plugin-file.php
	 */
	public function add_onboarding_popup_screen() {
		
		if ( $this->is_valid_page_screen() ) {
			
			require_once plugin_dir_path( __FILE__ ) . '/on-boarding/makewebbetter-onboarding-template-display.php';
		}
	}

	/**
	 * Validate current screen.
	 *
	 * @since    1.0.0
	 */
	public function is_valid_page_screen(){

		$screen = get_current_screen();

		if ( ! empty( $screen->id ) ) {

			return in_array( $screen->id, apply_filters( 'mwb_helper_valid_frontend_screens' , array( 'toplevel_page_upsell-order-bump-offer-for-woocommerce-setting' ) ) );
		}
	}

// End of Class.
}