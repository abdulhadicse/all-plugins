<?php
/**
 * Plugin Name: Latest Posts
 * Description: A demo widgets that display 5 latest posts
 * Plugin URI: https://www.github.com/abdulhadicse/
 * Author: Abdul Hadi
 * Author URI: https://www.abdulhadi.info
 * Version: 1.0
 * License: GPL2 or later
 * Text Domain: latest-posts
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class Display_Latest_Post {

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
     * @return \Display_Latest_Post
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
        define( 'DLP_LATEST_POST_VERSION', self::version );
        define( 'DLP_LATEST_POST_FILE', __FILE__ );
        define( 'DLP_LATEST_POST_PATH', __DIR__ );
        define( 'DLP_LATEST_POST_URL', plugins_url( '', DLP_LATEST_POST_FILE ) );
        define( 'DLP_LATEST_POST_ASSETS', DLP_LATEST_POST_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        new DLP\Frontend();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'dsp_latest_post_installed' );

        if ( ! $installed ) {
            update_option( 'dsp_latest_post_installed', time() );
        }

        update_option( 'dsp_latest_post_version', DLP_LATEST_POST_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Display_Latest_Post
 */
function function_name() {
    return Display_Latest_Post::init();
}

// kick-off the plugin
function_name();