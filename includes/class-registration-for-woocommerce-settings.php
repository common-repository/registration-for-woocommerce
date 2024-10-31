<?php
/**
 * WooCommerce General Settings
 *
 * @package WooCommerce/Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Registration_for_woocommerce_settings', false ) ) {
	return new Registration_for_woocommerce_settings();
}

/**
 * Registration_for_woocommerce_settings.
 */
class Registration_for_woocommerce_settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'registration-for-woocommerce';
		$this->label = __( 'Registration Form', 'registration-for-woocommerce');

		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$required_billing_fields = wcr_get_billing_fields_label_meta();
		unset( $required_billing_fields['billing_address_title'] );

		$required_shipping_fields = wcr_get_shipping_fields_label_meta();
		unset( $required_shipping_fields['shipping_address_title'] );


		$settings = apply_filters(
			'woocommerce_general_settings', array(

				array(
					'title' => __( 'Registration Form', 'registration-for-woocommerce'),
					'type'  => 'title',
					'desc'  => __( 'Settings for woocommerce registration.', 'registration-for-woocommerce'),
					'id'    => 'store_address',
				),

				array(
					'title'             => __( 'Layout', 'registration-for-woocommerce'),
					'desc'              => __( 'Layout to appear woocommerce registration form in frontend.', 'registration-for-woocommerce' ),
					'id'                => 'wcr_woocommerce_layout',
					'css'               => 'width:50px;',
					'default'           => 'one-column',
					'desc_tip'          => true,
					'type'              => 'select',
					'class'    			=> 'wc-enhanced-select',
					'options'			=> array(
							'one-column'	=> 'One Column',
							'two-column'	=> 'Two Column'
						),
				),

				array(
					'title'             => __( 'Include WooCommerce Billing Fields', 'registration-for-woocommerce'),
					'desc'              => __( 'Billing Fields to appear in woocommerce registration form.', 'registration-for-woocommerce' ),
					'id'                => 'wcr_woocommerce_billing_fields',
					'css'               => 'width:50px;',
					'default'           => array_keys( wcr_get_billing_fields_label_meta() ),
					'desc_tip'          => true,
					'type'              => 'multiselect',
					'class'    			=> 'wc-enhanced-select',
					'options'			=> wcr_get_billing_fields_label_meta()
				),

				array(
					'title'             => __( 'Required (*) Billing Fields', 'registration-for-woocommerce'),
					'desc'              => __( 'Billing Fields to appear in woocommerce registration form.', 'registration-for-woocommerce' ),
					'id'                => 'wcr_woocommerce_required_billing_fields',
					'css'               => 'width:10px;',
					'default'           => wcr_get_default_required_billing_fields(),
					'desc_tip'          => true,
					'type'              => 'multiselect',
					'class'    			=> 'wc-enhanced-select',
					'options'			=> $required_billing_fields,
				),

				array(
					'title'             => __( 'Different Shipping', 'registration-for-woocommerce'),
					'desc'              => __( 'Show ship to different address checkbox field', 'registration-for-woocommerce' ),
					'id'                => 'wcr_woocommerce_separate_shipping',
					'css'               => 'width:50px;',
					'default'           => 'yes',
					'desc_tip'          => true,
					'type'              => 'checkbox',
				),

				array(
					'title'             => __( 'Include WooCommerce Shipping Fields', 'registration-for-woocommerce'),
					'desc'              => __( 'Shipping Fields to appear in woocommerce registration form.', 'registration-for-woocommerce' ),
					'id'                => 'wcr_woocommerce_shipping_fields',
					'css'               => 'width:50px;',
					'default'           => array_keys( wcr_get_shipping_fields_label_meta() ),
					'desc_tip'          => true,
					'type'              => 'multiselect',
					'class'    			=> 'wc-enhanced-select',
					'options'			=> wcr_get_shipping_fields_label_meta(),
				),

				array(
					'title'             => __( 'Required (*) Shipping Fields', 'registration-for-woocommerce' ),
					'desc'              => __( 'Billing Fields to appear in woocommerce registration form.', 'registration-for-woocommerce' ),
					'id'                => 'wcr_woocommerce_required_shipping_fields',
					'css'               => 'width:50px;',
					'default'           => wcr_get_default_required_shipping_fields(),
					'desc_tip'          => true,
					'type'              => 'multiselect',
					'class'    			=> 'wc-enhanced-select',
					'options'			=> $required_shipping_fields
				),

				array(
					'type' => 'sectionend',
					'id'   => 'pricing_options',
				),
			)
		);

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		$settings = $this->get_settings();
		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
	}
}

return new Registration_for_woocommerce_settings();
