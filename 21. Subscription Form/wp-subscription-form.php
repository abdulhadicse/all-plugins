<?php
/**
 * Plugin Name: WP Subscription Form
 * Description: This is subscription plugin that collect email from user and save user info into mailchimp.
 * Plugin URI: https://www.github.com/abdulhadicse/
 * Author: Abdul Hadi
 * Author URI: https://www.abdulhadi.info
 * Version: 1.0
 * License: GPL2 or later
 * Text Domain: wps-form
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class WPS_Form {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
     * Class construcotr
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \WPS_Form
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WPS_FORM_VERSION', self::version );
        define( 'WPS_FORM_FILE', __FILE__ );
        define( 'WPS_FORM_PATH', __DIR__ );
        define( 'WPS_FORM_URL', plugins_url( '', WPS_FORM_FILE ) );
        define( 'WPS_FORM_ASSETS', WPS_FORM_URL . '/assets' );
    }

    /**
     * Initialize plugin for localization
     *
     * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( 'wps-form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }


    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        new WPCFORM\Assets();
        if( is_admin() ) {
            new WPCFORM\Admin();
        }
            new WPCFORM\Frontend();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'wps_form_installed' );

        if ( ! $installed ) {
            update_option( 'wps_form_installed', time() );
        }

        update_option( 'wps_form_version', WPS_FORM_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \WPS_Form
 */
function wps_form() {
    return WPS_Form::init();
}

// kick-off the plugin
wps_form();

