<?php
/**
 * Plugin Name: Cat Fact Widgets
 * Description: A demo widgets plugin that display 5 posts.
 * Plugin URI: https://github.com/abdulhadicse/cat-fact
 * Author: Abdul Hadi
 * Author URI: https://www.abdulhadi.info
 * Version: 1.0
 * License: GPL2 or later
 * Text Domain: cat-fact
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class Cat_Fact {

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
     * @return \Cat_Fact
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
        define( 'CAT_FACT_VERSION', self::version );
        define( 'CAT_FACT_FILE', __FILE__ );
        define( 'CAT_FACT_PATH', __DIR__ );
        define( 'CAT_FACT_URL', plugins_url( '', CAT_FACT_FILE ) );
        define( 'CAT_FACT_ASSETS', CAT_FACT_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
            new CATFACT\Frontend();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'cat_fact_installed' );

        if ( ! $installed ) {
            update_option( 'cat_fact_installed', time() );
        }

        update_option( 'cat_fact_version', CAT_FACT_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Cat_Fact
 */
function cat_fact() {
    return Cat_Fact::init();
}

// kick-off the plugin
cat_fact();