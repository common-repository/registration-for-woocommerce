<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Registration_for_woocommerce Class.
 *
 * @class   Registration_for_woocommerce
 * @version 1.0.0
 */
final class Registration_for_woocommerce {

	/**
	 * Plugin version.
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Instance of this class.
	 * @var object
	 */
	protected static $_instance = null;

	/*
	 * Return an instance of this class.
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'registration-for-woocommerce' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'registration-for-woocommerce' ), '1.0' );
	}

	/**
	 * WPForms Entries Constructor.
	 */
	public function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		if ( defined( 'WC_VERSION' )  ) {
			$this->define_constants();
			$this->includes();

		}
		else {
			add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
		}

		do_action( 'registration_for_woocommerce_loaded' );
	}

	/**
	 * Define FT Constants.
	 */
	private function define_constants() {
		$this->define( 'WCR_ABSPATH', dirname( WCR_PLUGIN_FILE ) . '/' );
		$this->define( 'WCR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'WCR_VERSION', $this->version );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name
	 * @param string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 *
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/registration-for-woocommerce/registration-for-woocommerce-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/registration-for-woocommerce-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'registration-for-woocommerce' );

		load_textdomain( 'registration-for-woocommerce', WP_LANG_DIR . '/registration-for-woocommerce/registration-for-woocommerce-' . $locale . '.mo' );
		load_plugin_textdomain( 'registration-for-woocommerce', false, plugin_basename( dirname( WCR_PLUGIN_FILE ) ) . '/languages' );
	}


	/**
	 * Includes.
	 */
	private function includes() {

		include_once WCR_ABSPATH . '/includes/functions-registration-for-woocommerce.php';

		if ( $this->is_request( 'admin' ) ) {
		}
	}

	/**
	 * woocommerce compatibility notice.
	 */
	public function woocommerce_missing_notice() {
		echo '<div class="error notice is-dismissible"><p>' . sprintf( esc_html__( 'Please install woocommerce plugin to use registration-for-woocommerce addon.',  'registration-for-woocommerce' ) ) .'</div>';
	}
}