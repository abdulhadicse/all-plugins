<?php
/**
 * Post View Shortcode
 *
 * @package           Post View Shortcode
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Post View Shortcode
 * Plugin URI:        https://wedevs.com
 * Description:       This plugin excerpt post and store the post excerpt in to db.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        https://wedevs.com
 * Text Domain:       post_excerpt
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
final class PV_Post_View {
/**
 * Plugin version 1.0
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
     * Call post_view_count methods
     * to count each post views
     * 
     * @since 1.0
     * 
     * @param $post The Post Object
     * 
     * @return WP_Post $post The Post Object
     */
    public function post_view_count( $post ) {
        if ( is_single() && is_singular( 'post' ) ) {
            $post_id = get_the_ID();
            $count_key = 'view_count';
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

        return $post;
    }

    /**
     * Combine attributes with known attributes
     * and fill in defaults if user not given.
     *
     * @since 1.0.0
     *
     * @param array  $atts  defined attributes.
     *
     * @return array filtered attribute list.
     */
    public function show_excerpt_post_data( $atts ) {
        $atts = shortcode_atts(
            array(
                'display'  => '10',
                'category' => '',
                'id'       => '',
                'order'    => 'ASC',
            ), $atts );

        global $args;
        $args = array(
            'post-type'      => 'post',
            'posts_per_page' => $atts['display'],
            'category_name'  => $atts['category'],
            'orderby'        => 'meta_value_num',
            'order'          => $atts['order'],
            'meta_key'       => 'view_count',
        );
        //check if ID is empty or not
        if ( !empty( $atts['id'] ) ) {
            $ids = explode( ",", $atts['id'] );
            $args = array(
                'post-type'      => 'post',
                'posts_per_page' => $atts['display'],
                'order'          => $atts['order'],
                'orderby'        => 'meta_value_num',
                'meta_key'       => 'view_count',
                'post__in'       => $ids,
            );
        }

        $query = new WP_Query( $args );

        // The Loop
        if ( $query->have_posts() ) {
            echo '<div>';
            while ( $query->have_posts() ) {
                $query->the_post();
                //show the excerpt of post data
                echo '<p>' . the_title() . '</p>';
                echo '<span>' . 'View Posts:' . $query->post->view_count . '</span>';
                echo '<br/>';
                echo '<br/>';
            }
            echo '</div>';
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();
    }

    /**
     * Initializes a singleton instance
     *
     * @return \PV_Post_View
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
        add_action( 'the_content', [$this, 'post_view_count'] );
        // Register shortcode
        add_shortcode( 'show_excerpt_post', [$this, 'show_excerpt_post_data'] );
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
 * @return \PV_Post_View
 */
function wedevs_academy() {
    return PV_Post_View::init();
}

// kick-off the plugin
wedevs_academy();
