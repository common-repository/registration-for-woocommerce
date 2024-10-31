=== Extended Registration Form For WooCommerce ===
Contributors: salive1
Tags: registration-form, woocommerce, billing, shipping, fields 
Requires at least: 4.0
Tested up to: 4.9
Requires PHP: 5.3
Stable tag: 1.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Extended Registration Form For WooCommerce

== Description ==

Extends the registration form of WooCommerce; Allow user to enter billing and shipping address fields directly during registration.

== Setup ==

1. Navigate to `WooCommerce->Settings->Accounts And Privacy`, check -> Allow customers to create an account during checkout.
2. Enter the shortcode `[woocommerce_my_account]`; appears extended registration form if user is not logged in.
3. Navigate to WooCommerce->Settings->Registration Form, customize your settings.

### Features And Options:

* Choose billing and shipping fields to display and save
* Multiple layouts
* Separate shipping field
* Well Documented
* Translation ready

== Frequently Asked Questions ==

= What is the plugin license? =

* This plugin is released under a GPL license.

= How to reorder the WooCommerce fields?

* You can use the provided filter hook `wcr_get_billing_fields_label_meta` default order is:
		
	`'billing_address_title'	=> __('Title','registration-for-woocommerce'),
		'billing_first_name'	    => __('First Name','registration-for-woocommerce'),
		'billing_last_name'		    => __('Last Name','registration-for-woocommerce'),
		'billing_company'			=> __('Company','registration-for-woocommerce'),
		'billing_address_1'			=> __('Address 1','registration-for-woocommerce'),
		'billing_address_2'			=> __('Address 2','registration-for-woocommerce'),
		'billing_country'			=> __('Country','registration-for-woocommerce'),
		'billing_city'				=> __('City','registration-for-woocommerce'),
		'billing_state'				=> __('State','registration-for-woocommerce'),
		'billing_postcode'			=> __('Postcode','registration-for-woocommerce'),
	'billing_email'				=> __('Email','registration-for-woocommerce'),
	'billing_phone'				=> __('Phone','registration-for-woocommerce'),`

and, similar to shipping fields.

== Screenshots ==

1. Settings page
2. Two column layout
3. One column layout

== Changelog ==

= 1.0.0 - 22/08/2018 =
* Initial Release