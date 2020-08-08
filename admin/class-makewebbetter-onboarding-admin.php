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

			wp_register_style( 'select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css', false, '1.0', 'all' );
		    wp_enqueue_style( 'select2css' );
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

			wp_register_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js', array( 'jquery' ), '1.0', true );
		    wp_enqueue_script( 'select2' );
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
	public function is_valid_page_screen() {

		$screen = get_current_screen();

		if ( ! empty( $screen->id ) ) {

			return in_array( $screen->id, apply_filters( 'mwb_helper_valid_frontend_screens' , array( 'toplevel_page_upsell-order-bump-offer-for-woocommerce-setting' ) ) );
		}
	}

	/**
	 * Add your onboarding form fields.
	 *
	 * @since    1.0.0
	 */
	public function add_on_boarding_form_fields() {

		$current_user = wp_get_current_user();
		if ( ! empty( $current_user ) ) {
			$current_user_email = $current_user->user_email ? $current_user->user_email : '';
		}

		/**
		 * Do not repeat id index.
		 */
		
		$fields = array(
			
			/**
			 * Input field with label.
			 * Radio field with label ( select only one ).
			 * Radio field with label ( select multiple one ).
			 * Checkbox radio with label ( select only one ).
			 * Checkbox field with label ( select multiple one ).
			 * Only Label ( select multiple one ).
			 * Select field with label ( select only one ).
			 * Select2 field with label ( select multiple one ).
			 * Email field with label. ( auto filled with admin email )
			 */

			rand() => array(
				'id' => 'my-name',
				'label' => esc_html__( 'Your name?' ),
				'type' => 'text',
				'name' => '',
				'value' => '',
				'required' => 'yes',
				'extra-class' => '',
			),

			rand() => array(
				'id' => 'my-gender',
				'label' => esc_html__( 'He or She?' ),
				'type' => 'radio',
				'name' => 'my-gender',
				'value' => '',
				'multiple' => 'no',
				'required' => 'yes',
				'extra-class' => '',
				'options' => array(
					'male' => 'I\'m a Male',
					'female' => 'I\'m a Female',
					'deny' => 'Deny to admin.',
				),
			),

			rand() => array(
				'id' => 'my-hobby',
				'label' => esc_html__( 'Where do you hangout?' ),
				'type' => 'radio',
				'name' => 'my-gender',
				'value' => '',
				'multiple' => 'yes',
				'required' => 'yes',
				'extra-class' => '',
				'options' => array(
					'out' => 'I\'m a Cricket guy',
					'in' => 'I\'m a Chess guy',
					'nerd' => 'Nothing Much',
				),
			),

			rand() => array(
				'id' => 'my-plugins',
				'label' => esc_html__( 'Where plugin have you used?' ),
				'type' => 'checkbox',
				'name' => 'my-plugins',
				'value' => '',
				'multiple' => 'yes',
				'required' => 'yes',
				'extra-class' => '',
				'options' => array(
					'order-bump' => 'Upsell Order Bump Offer for WooCommerce',
					'one-click-upsell' => 'WooCommerce One Click Upsell Funnel Pro',
					'hubsopt' => 'HubSpot for WooCommerce',
				),
			),

			rand() => array(
				'id' => 'how-you-use',
				'label' => esc_html__( 'How do you use it?' ),
				'type' => 'select',
				'name' => 'how-you-use',
				'value' => '',
				'multiple' => 'no',
				'required' => 'yes',
				'extra-class' => '',
				'options' => array(
					'order-bump-new' => 'Upsell Order Bump Offer for WooCommerce',
					'one-click-upsell-new' => 'WooCommerce One Click Upsell Funnel Pro',
					'hubsopt-new' => 'HubSpot for WooCommerce',
				),
			),

			rand() => array(
				'id' => 'how-you-use',
				'label' => esc_html__( 'What else we have got ah?' ),
				'type' => 'select2',
				'name' => 'how-you-use',
				'value' => '',
				'multiple' => 'yes',
				'required' => 'yes',
				'extra-class' => '',
				'options' => array(
					'giftcard' => 'Giftcard',
					'rma-ltie' => 'RMA',
					'social-new' => 'Social Media posting',
				),
			),

			rand() => array(
				'id' => 'onboard-email',
				'label' => esc_html__( 'Where to Send Gifts?' ),
				'type' => 'email',
				'name' => '',
				'value' => $current_user_email,
				'required' => 'yes',
				'extra-class' => '',
			),

			rand() => array(
				'id' => '',
				'label' => esc_html__( 'Okay Nice to meet you!!' ),
				'type' => 'label',
				'name' => '',
				'value' => '',
				'required' => '',
				'extra-class' => '',
			),
		);

		return $fields;
	}

	/**
	 * Returns form fields html.
	 *
	 * @since    1.0.0
	 */
	public function render_field_html( $attr=array() ) {

		$id 	= ! empty( $attr[ 'id' ] ) ? $attr[ 'id' ] : '';
		$name 	= ! empty( $attr[ 'name' ] ) ? $attr[ 'name' ] : '';
		$label 	= ! empty( $attr[ 'label' ] ) ? $attr[ 'label' ] : '';
		$type 	= ! empty( $attr[ 'type' ] ) ? $attr[ 'type' ] : '';
		$class 	= ! empty( $attr[ 'extra-class' ] ) ? $attr[ 'extra-class' ] : '';
		$value 	= ! empty( $attr[ 'value' ] ) ? $attr[ 'value' ] : '';
		$options 	= ! empty( $attr[ 'options' ] ) ? $attr[ 'options' ] : array();
		$multiple 	= ! empty( $attr[ 'multiple' ] ) && 'yes' == $attr[ 'multiple' ] ? 'yes' : 'no';
		$required 	= ! empty( $attr[ 'required' ] ) ? 'required="required"' : '';

		$html = '<div class ="mwb-form-single-field">';
		switch ( $type ) {

			case 'radio':
			    
			    // If field requires multiple answers.
			    if ( ! empty( $options ) && is_array( $options ) ) {

			    	$html .= '<label class="on-boarding-label" for="'. esc_attr( $id ) .'">' . esc_html( $label ) . '</label>';

			    	$is_multiple = ! empty( $multiple ) && 'yes' != $multiple ? 'name = "' . $name  . '"' : '';
			    	foreach ( $options as $option_value => $option_label ) {

						$html .= '<label class="on-boarding-field-label" for="'. esc_attr( $option_value ) .'">' . esc_html( $option_label ) . '</label>';
						$html .= '<input type="' . esc_attr( $type ) . '" class="on-boarding-' . esc_attr( $type ) . '-field' . esc_attr( $class ) . '" value="' . esc_attr( $option_value ) . '" id="' . esc_attr( $option_value ) . '" ' . esc_attr( $required ) . ' ' . $is_multiple . ' >';
			    	}
			    }

			    break;

			case 'checkbox':
			   
			   // If field requires multiple answers.
			    if ( ! empty( $options ) && is_array( $options ) ) {

			    	$html .= '<label class="on-boarding-label"  for="'. esc_attr( $id ) .'">' . esc_html( $label ) . '</label>';
					
			    	foreach ( $options as $option_id => $option_label ) {
			   
						$html .= '<label class="on-boarding-field-label" for="'. esc_attr( $option_id ) .'">' . esc_html( $option_label ) . '</label>';
						$html .= '<input type="' . esc_attr( $type ) . '" class="on-boarding-' . esc_attr( $type ) . '-field ' . esc_attr( $class ) . '" value="' . esc_attr( $value ) . '" id="' . esc_attr( $option_id ) . '" ' . esc_attr( $required ) . ' multiple="' . $multiple . '">';
			    	}
			    }

			    break;

			case 'select':
			case 'select2':

			   // If field requires multiple answers.
			    if ( ! empty( $options ) && is_array( $options ) ) {
					
					$is_multiple = 'yes' == $multiple ? 'multiple': '';
					$select2 = ( 'yes' == $multiple && 'select' == $type ) || 'select2' == $type ? 'on-boarding-select2 ': '';

					$html .= '<label class="on-boarding-label"  for="'. esc_attr( $id ) .'">' . esc_html( $label ) . '</label>';
					$html .= '<select class="on-boarding-select-field ' . esc_attr( $select2 ) . esc_attr( $class ) . '" id="'. esc_attr( $id ) .'" name="'. esc_attr( $name ) .'[]" ' . $required . ' ' . $is_multiple . '>';

						if ( 'select' == $type ) {
							
							$html .= '<option class="on-boarding-options" value="">' . esc_html( 'Select Any One Option...', 'textdomain' ) . '</option>';
						}

				    	foreach ( $options as $option_value => $option_label ) {
							$html .= '<option class="on-boarding-options" value="' . esc_attr( $option_value ) . '">' . esc_attr( $option_label ) . '</option>';
				    	}

					$html .= '</select>';
			    }

			    break;

			case 'label':

				/**
				 * Only a text in label.
				 */
				$html .= '<label class="on-boarding-label ' . esc_attr( $class ) . '" for="'. esc_attr( $id ) .'">' . esc_html( $label ) . '</label>';
				break;

			default:
				
				/**
				 * Text/ Password/ Email.
				 */
				$html .= '<label class="on-boarding-label" for="'. esc_attr( $id ) .'">' . esc_html( $label ) . '</label>';
				$html .= '<input type="' . esc_attr( $type ) . '" class="on-boarding-' . esc_attr( $type ) . '-field' . esc_attr( $class ) . '" value="' . esc_attr( $value ) . '"  name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" ' . esc_attr( $required ) . ' >';
		}

		$html .= '</div>';

		return $html;
	}

// End of Class.
}