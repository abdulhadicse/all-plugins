<?php
/**
 * Plugin Name: Related Posts
 * Description: A demo widgets plugin that display related posts a single post sidebar.
 * Plugin URI: https://github.com/abdulhadicse/cat-fact
 * Author: Abdul Hadi
 * Author URI: https://www.abdulhadi.info
 * Version: 1.0
 * License: GPL2 or later
 * Text Domain: related-post
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class Display_Related_Post {

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
     * @return \Display_Related_Post
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
        define( 'RELATED_POST_VERSION', self::version );
        define( 'RELATED_POST_FILE', __FILE__ );
        define( 'RELATED_POST_PATH', __DIR__ );
        define( 'RELATED_POST_URL', plugins_url( '', RELATED_POST_FILE ) );
        define( 'RELATED_POST_ASSETS', RELATED_POST_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
            new RelatedPost\Frontend();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'related_post_installed' );

        if ( ! $installed ) {
            update_option( 'related_post_installed', time() );
        }

        update_option( 'related_post_version', RELATED_POST_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Display_Related_Post
 */
function related_post() {
    return Display_Related_Post::init();
}

// kick-off the plugin
related_post();