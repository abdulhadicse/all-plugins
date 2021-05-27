<?php
/**
 * Modify Post Title Plugin
 *
 * @package           Modify Post Title
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Modify Post Title
 * Plugin URI:        https://www.github.com/abdulhadicse/all-plugins
 * Description:       A tutorial plugin for modify (capitalize) post title.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       modify_post_title
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class WeDevs_Academy {

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
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        add_filter( 'wp_insert_post_data', [$this, 'set_post_title_capitalize'] );
    }

    /**
        * Call set title capitalize methods
        * Set capitalize title
        *
        * @return Array
    */
    public function set_post_title_capitalize( $data ) {
        $capitalize         = ucwords($data['post_title']); 
        $data['post_title'] = $capitalize;

        return $data;
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'wd_academy_installed' );

        if ( ! $installed ) {
            update_option( 'wd_academy_installed', time() );
        }
        
        update_option( 'wd_academy_version', WD_ACADEMY_VERSION );
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WD_ACADEMY_VERSION', self::version );
        define( 'WD_ACADEMY_FILE', __FILE__ );
        define( 'WD_ACADEMY_PATH', __DIR__ );
        define( 'WD_ACADEMY_URL', plugins_url( '', WD_ACADEMY_FILE ) );
        define( 'WD_ACADEMY_ASSETS', WD_ACADEMY_URL . '/assets' );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \WeDevs_Academy
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

}

/**
 * Initializes the main plugin
 *
 * @return \WeDevs_Academy
 */
function wedevs_academy() {
    return WeDevs_Academy::init();
}

// kick-off the plugin
wedevs_academy();
