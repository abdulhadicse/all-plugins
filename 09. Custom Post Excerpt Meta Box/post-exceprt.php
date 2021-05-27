<?php
/**
 * Post Excerpt Plugin
 *
 * @package           Post Excerpt
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Post Excerpt
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
final class Pe_Post_Excerpt {
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
            ), $atts );
        //convet string to array
        $ids = explode( ",", $atts['id'] );
        global $args;
        
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $atts['display'],
            'meta_key'       => '_excerpt_meta_key',
        );
        //check category is empty or not
        if ( '' !== $atts['category'] ) {
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => $atts['display'],
                'meta_key'       => '_excerpt_meta_key',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'slug',
                        'terms'    => $atts['category'],
                    ),
                ),
            );
        }
        //check id is empty or not
        if ( '' !== $atts['id'] ) {
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => $atts['display'],
                'meta_key'       => '_excerpt_meta_key',
                'post__in'       => $ids,
            );
        }

        $query = new WP_Query( $args );

        // The Loop
        if ( $query->have_posts() ) {
            echo '<ul>';
            while ( $query->have_posts() ) {
                $query->the_post();
                //show the excerpt of post data
                echo '<li>' . $query->post->_excerpt_meta_key . '</li>';
            }
            echo '</ul>';
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();
    }

    /**
     * save the excerpt field value in the
     * _excerpt_meta_key meta key.
     *
     * @since 1.0.0
     *
     * @param int $post_id The Post ID
     *
     * @return void
     */
    public function save_excerpt_post_data( $post_id ) {
        $excerpt_text = isset( $_POST['excerpt'] ) ? sanitize_text_field( $_POST['excerpt'] ) : '';
        //check the condition if excerpt_text is emplty
        if ( '' == $excerpt_text ) {
            return;
        }
        update_post_meta( $post_id, '_excerpt_meta_key', $excerpt_text );
    }

    /**
     * add_post_excerpt_data_field function will
     * hold the HTML for the meta box
     *
     * @since 1.0
     *
     * @param WP_Post $post Post object.
     *
     * @return void
     */
    public function add_post_excerpt_data_field( $post ) {
        $excerpt_value = get_post_meta( $post->ID, '_excerpt_meta_key', true );
        ?>
        <textarea id="excerpt" name="excerpt" rows="4" cols="116"><?php echo esc_html( $excerpt_value ); ?></textarea>
        <?php
    }

    /**
     * adding a meta box to the post edit screen
     *
     * @since 1.0
     *
     * @return void
     */
    public function post_excerpt_meta_box() {
        /**
         * Adds a meta
         * box to one
         * or more screens.
         *
         * @since 1.0
         *
         * @param mixed
         */
        add_meta_box(
            'post_excerpt',
            __( 'Excerpt Post', 'post_excerpt' ),
            [$this, 'add_post_excerpt_data_field'],
            'post',
            'normal'
        );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Pe_Post_Excerpt
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
        define( 'POST_EXCERPT_VERSION', self::version );
        define( 'POST_EXCERPT_FILE', __FILE__ );
        define( 'POST_EXCERPT_PATH', __DIR__ );
        define( 'POST_EXCERPT_URL', plugins_url( '', POST_EXCERPT_FILE ) );
        define( 'POST_EXCERPT_ASSETS', POST_EXCERPT_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        add_action( 'add_meta_boxes', [$this, 'post_excerpt_meta_box'] );
        add_action( 'save_post', [$this, 'save_excerpt_post_data'] );
        // Register shortcode
        add_shortcode( 'show_excerpt_post', [$this, 'show_excerpt_post_data'] );
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'post_excerpt_installed' );

        if ( !$installed ) {
            update_option( 'post_excerpt_installed', time() );
        }
        update_option( 'post_excerpt_version', POST_EXCERPT_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Pe_Post_Excerpt
 */
function Pe_Post_Excerpt() {
    return Pe_Post_Excerpt::init();
}

// kick-off the plugin
Pe_Post_Excerpt();
