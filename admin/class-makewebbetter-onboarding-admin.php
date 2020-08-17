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

		    wp_enqueue_style( 'select2css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
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

		    wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/makewebbetter-onboarding-admin.js', array( 'jquery' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'mwb',
				array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'auth_nonce'    => wp_create_nonce( 'mwb_onboarding_nonce' ),
				)
			);
		}
	}

	/**
	 * Get all active plugins by MakeWebBetter.
	 *
	 * @since    1.0.0
	 * Help : replace/define CURRENT_PLUGIN_FILE with plugin-folder/plugin-file.php
	 */
	public function check_mwb_active_plugins() {
return;
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
		
		if ( $this->is_valid_page_screen() && empty( get_option( 'onboarding-data-sent' ) ) ) {
			
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
	 * Validate current screen.
	 *
	 * @since    1.0.0
	 */
	public function should_show_onboarding_popup() {

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

		$currency_symbol = get_woocommerce_currency_symbol();
		$store_name = get_the_title( wc_get_page_id( 'shop' ) );
		$store_url = get_home_url();

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
				'id' => 'monthly-revenue',
				'label' => esc_html__( 'What is your monthly revenue?', 'makewebbetter-onboarding' ),
				'type' => 'radio',
				'name' => 'monthly-revenue',
				'value' => '',
				'multiple' => 'no',
				'required' => 'yes',
				'extra-class' => '',
				'options' => array(
					'0-500' 		=> $currency_symbol . '0-' . $currency_symbol . '500',
					'501-5000'  		=> $currency_symbol . '501-' . $currency_symbol . '5000',
					'5001-10000' 		=> $currency_symbol . '5001-' . $currency_symbol . '10000',
					'10000+'  		=> $currency_symbol . '10000+'
				),
			),
 
			rand() => array(
				'id' => 'industry_type',
				'label' => esc_html__( 'What industry defines your business?', 'makewebbetter-onboarding' ),
				'type' => 'select',
				'name' => 'industry_type',
				'value' => '',
				'multiple' => 'yes',
				'required' => 'yes',
				'extra-class' => '',
				'options' => array(
					'agency' 				=> 'Agency',
					'consumer-services' 	=> 'Consumer Services',
					'ecommerce' 			=> 'Ecommerce',
					'financial-services' 	=> 'Financial Services',
					'healthcare' 			=> 'Healthcare',
					'manufacturing' 		=> 'Manufacturing',
					'nonprofit-and-education' => 'Nonprofit and Education',
					'professional-services' => 'Professional Services',
					'real-estate' 			=> 'Real Estate',
					'software' 				=> 'Software',
					'startups' 				=> 'Startups',
					'restaurant' 			=> 'Restaurant',
					'fitness' 				=> 'Fitness',
					'jewelry' 				=> 'Jewelry',
					'beauty' 				=> 'Beauty',
					'celebrity' 			=> 'Celebrity',
					'gaming' 				=> 'Gaming',
					'government' 			=> 'Government',
					'sports' 				=> 'Sports',
					'retail-store' 			=> 'Retail Store',
					'travel' 				=> 'Travel',
					'political-campaign' 	=> 'Political Campaign',
				),
			),

			rand() => array(
				'id' => 'onboard-email',
				'label' => esc_html__( 'What is the best email address to contact you?' ),
				'type' => 'email',
				'name' => 'onboard-email',
				'value' => $current_user_email,
				'required' => 'yes',
				'extra-class' => '',
			),

			rand() => array(
				'id' => 'onboard-number',
				'label' => esc_html__( 'What is your contact number?' ),
				'type' => 'text',
				'name' => 'onboard-number',
				'value' => '',
				'required' => 'yes',
				'extra-class' => '',
			),

			rand() => array(
				'id' => 'store-name',
				'label' => '',
				'type' => 'hidden',
				'name' => 'store-name',
				'value' => $store_name,
				'required' => '',
				'extra-class' => '',
			),

			rand() => array(
				'id' => 'store-url',
				'label' => '',
				'type' => 'hidden',
				'name' => 'store-url',
				'value' => $store_url,
				'required' => '',
				'extra-class' => '',
			),

			rand() => array(
				'id' => 'show-counter',
				'label' => '',
				'type' => 'hidden',
				'name' => 'show-counter',
				'value' => get_option( 'onboarding-show-counter', 'not-sent' ),
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
		
		$html = '';

		if ( $type != 'hidden' ) :
			$html = '<div class ="mwb-form-single-field">';
		endif;
		switch ( $type ) {

			case 'radio':
			    
			    // If field requires multiple answers.
			    if ( ! empty( $options ) && is_array( $options ) ) {

			    	$html .= '<label class="on-boarding-label" for="'. esc_attr( $id ) .'">' . esc_html( $label ) . '</label>';

			    	$is_multiple = ! empty( $multiple ) && 'yes' != $multiple ? 'name = "' . $name  . '"' : '';
			    	foreach ( $options as $option_value => $option_label ) {

			    		$html .= '<div class="mwb-on-boarding-radio-wrapper">';
			    		$html .= '<input type="' . esc_attr( $type ) . '" class="on-boarding-' . esc_attr( $type ) . '-field' . esc_attr( $class ) . '" value="' . esc_attr( $option_value ) . '" id="' . esc_attr( $option_value ) . '" ' . $required . ' ' . $is_multiple . ' >';
						$html .= '<label class="on-boarding-field-label" for="'. esc_attr( $option_value ) .'">' . esc_html( $option_label ) . '</label>';
						
						$html .= '</div>';
			    	}
			    }

			    break;

			case 'checkbox':
			   
			   // If field requires multiple answers.
			    if ( ! empty( $options ) && is_array( $options ) ) {

			    	$html .= '<label class="on-boarding-label"  for="'. esc_attr( $id ) .'">' . esc_html( $label ) . '</label>';
					
			    	foreach ( $options as $option_id => $option_label ) {
			   			
			   			$html .= '<div class="mwb-on-boarding-checkbox-wrapper">';
						
						$html .= '<input type="' . esc_attr( $type ) . '" class="on-boarding-' . esc_attr( $type ) . '-field ' . esc_attr( $class ) . '" value="' . esc_attr( $value ) . '" id="' . esc_attr( $option_id ) . '">';
						$html .= '<label class="on-boarding-field-label" for="'. esc_attr( $option_id ) .'">' . esc_html( $option_label ) . '</label>';
						$html .= '</div>';
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
				$html .= '<input type="' . esc_attr( $type ) . '" class="on-boarding-' . esc_attr( $type ) . '-field ' . esc_attr( $class ) . '" value="' . esc_attr( $value ) . '"  name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" ' .  $required  . ' >';
		}

		if ( $type != 'hidden' ) :
			$html .= '</div>';
		endif;

		return $html;
	}


	/**
	 * Send the data to MWB server.
	 *
	 * @since    1.0.0
	 */
	public function send_onboarding_data() {

		check_ajax_referer( 'mwb_onboarding_nonce', 'nonce' );

		$form_data = ! empty( $_POST[ 'form_data' ] ) ? json_decode( stripslashes( $_POST[ 'form_data' ] ) ) : '';
		$formatted_data = array();
		$formatted_data[ 'currency' ] = get_woocommerce_currency();

		if ( ! empty( $form_data ) && is_array( $form_data ) ) {

			foreach ( $form_data as $key => $input ) {
				if( false !== strrpos( $input->name, '[]' ) ) {

					$new_key = str_replace( '[]', '', $input->name );
					if ( empty( $formatted_data[ $new_key ] ) ) {
						$formatted_data[ $new_key ] = array();
					}

					array_push( $formatted_data[ $new_key ], $input->value );
				}

				else {

					$formatted_data[ $input->name ] = $input->value;
				}
			}
		}

		try {

			if ( ! empty( $formatted_data ) && is_array( $formatted_data ) ) {
				
				$email_body = $this->render_form_data_into_table( $formatted_data );
			}
			function set_temp_content_type(){
			    return "text/html";
			}
			add_filter( 'wp_mail_content_type','set_temp_content_type' );

			$email_to = 'mwbdev13@gmail.com';
			$email_subject = 'Email subject';
			$send_mail = wp_mail( $email_to, $email_subject, $email_body );

			remove_filter( 'wp_mail_content_type','set_temp_content_type' );

		} catch (Exception $e) {

			echo json_encode( $e->getMessage() );
			wp_die();
		}

		echo json_encode( $formatted_data );
		wp_die();
	}


	/**
	 * Covert array to html.
	 *
	 * @since    1.0.0
	 */
	public function render_form_data_into_table( $formatted_data='' ) {

		$email_body = '<table border="1" style="text-align:center;"><tr><th>Data</th><th>Value</th></tr>';
		foreach ( $formatted_data as $key => $value ) {
			
			$key = ucwords( str_replace( '_', ' ', $key ) );
			$key = ucwords( str_replace( '-', ' ', $key ) );

			if ( is_array( $value ) ) {
				
				$email_body .= '<tr><td>' . $key . '</td><td>';

				foreach ( $value as $k => $v ) {	
					$email_body .= ucwords( $v ) . '<br>';
				}

				$email_body .= '</td></tr>';
			}

			else {

				$email_body .= '  <tr><td>' . $key . '</td><td>' . ucwords( $value ) . '</td></tr>';
			}
		}

		$email_body .= '</table>';
		
		return $email_body;
	}

// End of Class.
}