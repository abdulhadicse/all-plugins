<?php
/**
 * Posts Views Count with Tag
 *
 * @package           Posts Views Count with Tag
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Posts Views Count with Tag
 * Plugin URI:        https://www.github.com/abdulhadicse/all-plugins
 * Description:       A tutorial plugin for display post views with filter hook
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       posts_views_count
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
final class VC_View_Count {

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
        add_action( 'init', [ $this, 'init_plugin' ] );
        add_filter( 'add_em_wrap_view', [$this, 'add_em_wrap_view_count'] );
    }

    /**
        * Call set views methods
        * Get post content with views
        *
        * @return String
    */

    public function set_post_views($post) {
        if ( is_single() ) {
            //Get post id & set count meta key
            $post_id   = get_the_ID();
            $count_key = 'post_views_count';
            $count     = get_post_meta($post_id, $count_key, true);
            
            // check the condition if count emplty or not
            if( $count=='' ) {
                $count = 0;
                add_post_meta($post_id, $count_key, '0');
            }
            else {
                $count++;
                update_post_meta($post_id, $count_key, $count);
            }            
        }
        
        //Call post views methods
        $content = $this->get_post_view();
        return $post . $content;     
    }

    /**
        * Call post views methods
        * Get post views count
        * Call add filter hook
        * Get wraping content using apply_filters
        *
        * @return String
    */
    public function get_post_view() {
        $get_count_post_view      = get_post_meta( get_the_ID(), 'post_views_count', true ); 
        $get_post_em_wrap_content = apply_filters( 'add_em_wrap_view', $get_count_post_view);
        
        return $get_post_em_wrap_content;
    }

    /**
        * Wraping content with post views count
        *
        * @return String
    */
    public function add_em_wrap_view_count( $count ) {
        $em_wrap_content = '<div>';
        $em_wrap_content .= '<em>';
        $em_wrap_content .= 'Post Views: ' . $count;
        $em_wrap_content .= '</em>';
        $em_wrap_content .= '</div>';

        return $em_wrap_content;
    }

    /**
     * Initializes a singleton instance
     *
     * @return \VC_View_Count
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
        define( 'VIEW_COUNT_VERSION', self::version );
        define( 'VIEW_COUNT_FILE', __FILE__ );
        define( 'VIEW_COUNT_PATH', __DIR__ );
        define( 'VIEW_COUNT_URL', plugins_url( '', VIEW_COUNT_FILE ) );
        define( 'VIEW_COUNT_ASSETS', VIEW_COUNT_URL . '/assets' );
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
        $installed = get_option( 'view_count_installed' );

        if ( ! $installed ) {
            update_option( 'view_count_installed', time() );
        }

        update_option( 'view_count_version', VIEW_COUNT_VERSION );
    }

    
}

/**
 * Initializes the main plugin
 *
 * @return \VC_View_Count
 */
function VC_View_Count() {
    return VC_View_Count::init();
}

// kick-off the plugin
VC_View_Count();
