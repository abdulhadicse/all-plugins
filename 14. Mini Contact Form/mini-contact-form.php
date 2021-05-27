<?php
/**
 * Plugin Name: Mini Contact Form
 * Description: This is a mini contact form
 * Plugin URI: https://github.com/abdulhadicse
 * Author: Abdul Hadi
 * Author URI: https://abdulhadi.info
 * Version: 1.0
 * Text Domain: mcf-form
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . "/vendor/autoload.php";

/**
 * The main plugin class
 */
final class Mini_Contact_Form {

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
     * @return \Mini_Contact_Form
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
        define( 'MCF_FORM_VERSION', self::version );
        define( 'MCF_FORM_FILE', __FILE__ );
        define( 'MCF_FORM_PATH', __DIR__ );
        define( 'MCF_FORM_URL', plugins_url( '', MCF_FORM_FILE ) );
        define( 'MCF_FORM_ASSETS', MCF_FORM_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {

        new ContactForm\Assets();
        
        if ( is_admin() ) {
            new ContactForm\Admin();
        }
            new ContactForm\Frontend();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $install = new ContactForm\Installer();
        $install-> run();
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Mini_Contact_Form
 */
function mini_contact_form() {
    return Mini_Contact_Form::init();
}

// kick-off the plugin
mini_contact_form();