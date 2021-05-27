<?php
/**
 * Post Views Count Plugin 
 *
 * @package           Posts Views Count
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Posts Views Count
 * Plugin URI:        https://www.github.com/abdulhadicse/all-plugins
 * Description:       A tutorial plugin for display a single post views.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       posts_views_count
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class PV_Post_View_Counter {
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

        register_activation_hook( __FILE__, [$this, 'activate'] );
        add_action( 'plugins_loaded', [$this, 'init_plugin'] );
    }

    /**
     * Call set views methods
     * Get post content with views
     * @return String
     */
    public function set_post_views( $post ) {
        if ( is_singular( 'post' ) ) {
            //get post id & set count meta key
            $post_id = get_the_ID();
            $count_key = 'post_views_count';

            $count = get_post_meta( $post_id, $count_key, true );

            // check the condition if count emplty or not
            if ( $count == '' ) {
                $count = 0;
                add_post_meta( $post_id, $count_key, '0' );
            } else {
                $count++;
                update_post_meta( $post_id, $count_key, $count );
            }
        }

        /**
         * Call post views methods
         * Get post count views
         */
        $content = $this->get_post_view();
        return $post . $content;
    }

    /**
     * call post views methods
     * get post views count
     * @return String
     */
    public function get_post_view() {
        $count = get_post_meta( get_the_ID(), 'post_views_count', true );
        $content = '<div>';
        $content .= 'Post Views : ' . $count;
        $content .= '</div>';

        return $content;
    }

    /**
     * Initializes a singleton instance
     *
     * @return \PV_Post_View_Counter
     */
    public static function init() {
        static $instance = false;

        if ( !$instance ) {
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
        define( 'POST_VIEW_VERSION', self::version );
        define( 'POST_VIEW_FILE', __FILE__ );
        define( 'POST_VIEW_PATH', __DIR__ );
        define( 'POST_VIEW_URL', plugins_url( '', POST_VIEW_FILE ) );
        define( 'POST_VIEW_ASSETS', POST_VIEW_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        add_filter( 'the_content', [$this, 'set_post_views'] );
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'post_view_installed' );

        if ( !$installed ) {
            update_option( 'post_view_installed', time() );
        }

        update_option( 'post_view_version', POST_VIEW_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \PV_Post_View_Counter
 */
function PV_Post_View_Counter() {
    return PV_Post_View_Counter::init();
}

// kick-off the plugin
PV_Post_View_Counter();
