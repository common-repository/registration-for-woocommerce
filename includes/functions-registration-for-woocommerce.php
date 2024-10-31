<?php

add_action( 'wp_enqueue_scripts', 'wcr_load_scripts' );
add_filter( 'woocommerce_get_settings_pages', 'wcr_include_woocommerce_registration_settings' );
add_action( 'woocommerce_register_form', 'wcr_woocommerce_extra_fields' );
add_action( 'woocommerce_register_post', 'wcr_errors_filtering', 10, 3 );
add_action( 'woocommerce_created_customer', 'wcr_save_fields', 10, 3 );

/**
 * Load scripts in frontend
 */
function wcr_load_scripts() {

	$layout = get_option( 'wcr_woocommerce_layout', 'one-column' );
	
	//Load css to two column layout only.
	if( 'two-column' === $layout ) {
		wp_enqueue_style( 'wcr-style', plugins_url( '/registration-for-woocommerce/assets/css/wcr.css' ), array(), WCR_VERSION, $media = 'all' );
	}

	$suffix           = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	wp_enqueue_script( 'wcr-script', plugins_url( '/registration-for-woocommerce/assets/js/frontend/wcr.js' ), array(), WCR_VERSION, $media = 'all' );
}

/**
 * Include woocommerce registration settings page
 * @return array
 */
function wcr_include_woocommerce_registration_settings( $settings ) {
	$settings[] = include_once WCR_ABSPATH . '/includes/class-registration-for-woocommerce-settings.php';
}

/**
 * Add html to woocommerce fields
 * @return void
 */
function wcr_woocommerce_extra_fields() { 

	$countries_obj   = new WC_Countries();
    $countries   = $countries_obj->__get('countries');

	$stored_billing_fields 			= get_option( 'wcr_woocommerce_billing_fields', array() );
	$stored_required_billing_fields = get_option( 'wcr_woocommerce_required_billing_fields', array() );
	$all_billing_fields    			= wcr_get_billing_fields_label_meta();

	$stored_shipping_fields 			= get_option( 'wcr_woocommerce_shipping_fields', array() );
	$stored_required_shipping_fields 	= get_option( 'wcr_woocommerce_required_shipping_fields', array() );
	$all_shipping_fields    			= wcr_get_shipping_fields_label_meta();

	$required = '<span class="required">*</span>';

	echo '<div class="wcr-row">';
	echo '<div class="wcr-column">';

	if( in_array( 'billing_address_title', $stored_billing_fields ) ) {	
	    ?>		
	    	<label for="billing_address"><b><?php esc_html_e( 'Billing Address', 'registration-for-woocommerce' ); ?></b>&nbsp;</label>
		<?php
	}

	unset( $all_billing_fields['billing_address_title'] );

	foreach( $all_billing_fields as $key => $billing_fields ) {

		if( 'billing_country' === $key && in_array( 'billing_country', $stored_billing_fields ) ) {	
			    ?>	
			    	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="billing_country"><?php echo esc_html__( 'Country', 'registration-for-woocommerce' ); ?>&nbsp;<?php echo in_array( 'billing_country', $stored_required_billing_fields ) ? $required : '';?></label>
			    	<select name="billing_country" id="billing_country" >
						<option value=""><?php esc_html_e( 'Select a country&hellip;', 'registration-for-woocommerce' ); ?></option>
						<?php
							foreach ( $countries as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
							}
						?>
					</select>
				<?php
		}

		if( in_array( $key, $stored_billing_fields ) ) {
			?>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="<?php echo $key;?>"><?php echo $billing_fields; ?>&nbsp;<?php echo in_array( $key, $stored_required_billing_fields ) ? $required : '';?></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="<?php echo $key;?>" id="<?php echo $key;?>" autocomplete="email" value="<?php echo ( ! empty( $_POST[ $key ] ) ) ? esc_attr( wp_unslash( $_POST[ $key ] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>
			<?php
		}
	}

	do_action( 'registration_for_woocommerce_after_billing_address_fields' );

	echo '</div>';

	echo '<div class="wcr-column">';

	$separate_shipping = get_option( 'wcr_woocommerce_separate_shipping', 'no' );

	if( 'yes' === $separate_shipping ) {
		?>	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<input type="checkbox" class="woocommerce-Input woocommerce-Input--text input-text" name="separate_shipping" id="separate_shipping" autocomplete="" value="<?php echo ( ! empty( $_POST[ 'separate_shipping' ] ) ) ? esc_attr( wp_unslash( $_POST[ 'separate_shipping' ] ) ) : ''; ?>"  checked >
				<i><?php echo esc_html__( 'Ship to different address', 'registration-for-woocommerce' ); ?></i>
				</p>
		<?php
	}

	echo '<div class ="wcr-shipping woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide>';

	if( in_array( 'shipping_address_title', $stored_shipping_fields ) ) {	
	    ?>	
	    	<label for="shipping_address"><b><?php esc_html_e( 'Shipping Address', 'registration-for-woocommerce' ); ?></b>&nbsp;</label>
		<?php
	}

	unset( $all_shipping_fields['shipping_address_title'] );

	foreach( $all_shipping_fields as $key => $shipping_fields ) {

		if( 'shipping_country' === $key && in_array( 'shipping_country', $stored_shipping_fields ) ) {	
			    ?>	
			    	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="shipping_country"><?php echo esc_html__( 'Country', 'registration-for-woocommerce' ); ?>&nbsp;<?php echo in_array( 'shipping_country', $stored_required_shipping_fields ) ? $required : '';?></label>
			    	<select name="shipping_country" id="shipping_country" >
						<option value=""><?php esc_html_e( 'Select a country&hellip;', 'registration-for-woocommerce' ); ?></option>
						<?php
							foreach ( $countries as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
							}
						?>
					</select>
				<?php
		}

		if( in_array( $key, $stored_shipping_fields ) ) {
			?>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="<?php echo $key;?>"><?php echo $shipping_fields; ?>&nbsp;<?php echo in_array( $key, $stored_required_shipping_fields ) ? $required : '';?></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="<?php echo $key;?>" id="<?php echo $key;?>" autocomplete="email" value="<?php echo ( ! empty( $_POST[ $key ] ) ) ? esc_attr( wp_unslash( $_POST[ $key ] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</p>
			<?php
		}
	}

	echo '</div>';
	echo '</div>';
	echo '</div>';
}

/**
 * All woocommerce billing fields label with meta key
 * @return  array
 */
function wcr_get_billing_fields_label_meta() {
	return apply_filters( 'wcr_get_billing_fields_label_meta', array(
		'billing_address_title'	=> __('Title','registration-for-woocommerce'),
 		'billing_first_name'	=> __('First Name','registration-for-woocommerce'),
 		'billing_last_name'		=> __('Last Name','registration-for-woocommerce'),
 		'billing_company'		=> __('Company','registration-for-woocommerce'),
 		'billing_address_1'		=> __('Address 1','registration-for-woocommerce'),
 		'billing_address_2'		=> __('Address 2','registration-for-woocommerce'),
 		'billing_country'		=> __('Country','registration-for-woocommerce'),
 		'billing_city'			=> __('City','registration-for-woocommerce'),
 		'billing_state'			=> __('State','registration-for-woocommerce'),
 		'billing_postcode'		=> __('Postcode','registration-for-woocommerce'),
		'billing_email'			=> __('Email','registration-for-woocommerce'),
		'billing_phone'			=> __('Phone','registration-for-woocommerce'),
	) );
}

/**
 * All woocommerce shipping fields label with meta key
 * @return array
 */
function wcr_get_shipping_fields_label_meta() {
	return apply_filters( 'wcr_get_shipping_fields_label_meta', array( 
		'shipping_address_title'=> __('Title','registration-for-woocommerce'),
 		'shipping_first_name'	=> __('First Name','registration-for-woocommerce'),
 		'shipping_last_name'	=> __('Last Name','registration-for-woocommerce'),
 		'shipping_company'		=> __('Company','registration-for-woocommerce'),
 		'shipping_address_1'	=> __('Address 1','registration-for-woocommerce'),
 		'shipping_address_2'	=> __('Address 2','registration-for-woocommerce'),
 		'shipping_country'		=> __('Country','registration-for-woocommerce'),
 		'shipping_city'			=> __('City','registration-for-woocommerce'),
 		'shipping_state'		=> __('State','registration-for-woocommerce'),
 		'shipping_postcode'		=> __('Postcode','registration-for-woocommerce'),
	));
}

/**
 * Billing fields required by default
 * @return array
 *  
 */
function wcr_get_default_required_billing_fields() {
	return apply_filters( 'wcr_get_default_required_billing_fields', array(
		'billing_first_name',
		'billing_last_name',
	) );
}

/**
 * Shipping fields required by default
 * @return array
 */
function wcr_get_default_required_shipping_fields() {
	return apply_filters( 'wcr_get_default_required_shipping_fields', array(
		'shipping_first_name',
		'shipping_last_name',
	) );
}

/**
 * Error handling for frontend form submission.
 * @param  string $username
 * @param  string $email
 * @param  obj $errors
 * @return mixed
 */
function wcr_errors_filtering( $username, $email, $errors ) {

	$stored_billing_fields 			= get_option( 'wcr_woocommerce_billing_fields', array() );
	$stored_required_billing_fields = get_option( 'wcr_woocommerce_required_billing_fields', array() );

	$stored_shipping_fields 		 = get_option( 'wcr_woocommerce_shipping_fields', array() );
	$stored_required_shipping_fields = get_option( 'wcr_woocommerce_required_shipping_fields', array() );

	$stored_woocommerce_fields		 		 = array_merge( $stored_billing_fields, $stored_shipping_fields );
	$stored_woocommerce_required_fields		 = array_merge( $stored_required_billing_fields, $stored_required_shipping_fields );

	$woocommerce_fields = array_merge( wcr_get_billing_fields_label_meta(), wcr_get_shipping_fields_label_meta() );

	foreach( $stored_woocommerce_fields as $value ) {

		if( in_array( $value, $stored_woocommerce_required_fields ) && empty( $_POST[ $value ] ) ) {
			throw new Exception( '<strong>'. isset( $woocommerce_fields[ $value ] ) ? $woocommerce_fields[ $value ] . ' (' . $value . ') ' . __( 'is a required field.', 'registration-for-woocommerce' ) : $value . __( 'is a required field.', 'registration-for-woocommerce' ). '</strong>' );
		}
	}

	// Validate Emails.
	if( ! is_email( $_POST['billing_email'] ) || ! is_email( $_POST['shipping_email'] ) ) {
		throw new Exception( __('Billing and shipping emails should be valid email address!') );
	}

}

/**
 * Saving fields after woocommerce creates customer
 * @param  int $customer_id
 * @param  int $new_customer_data  customer data
 * @param  $password_generated Generated Password of customer
 * @return void
 */
function wcr_save_fields( $customer_id, $new_customer_data, $password_generated ) {

	$nonce_value = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';
	$nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? $_POST['woocommerce-register-nonce'] : $nonce_value;

	if ( ! empty( $_POST['register'] ) && wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {

		$woocommerce_fields = array_merge( wcr_get_billing_fields_label_meta(), wcr_get_shipping_fields_label_meta() );

		foreach( $woocommerce_fields as $key => $value ) {

			if( isset( $_POST[ $key ] ) ) {

				if( $key === 'billing_email' || $key === 'shipping_email' ) {
					$value = sanitize_email( $_POST[$key] );
				} else {
					$value = sanitize_text_field( $_POST[$key] );
				}

				update_user_meta( $customer_id, $key, $value );
			}
		}
	}
}