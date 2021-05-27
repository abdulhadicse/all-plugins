<?php
/**
 * Search Meta Data from Post Meta Table
 *
 * @package           Search Meta
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Search Meta
 * Plugin URI:        https://wedevs.com
 * Description:       This plugin search meta data from custom post type like book.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       search_meta
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class SearchMeta {
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
     * Initializes a singleton instance
     *
     * @return \WeDevs_Academy
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
        define( 'BR_BOOK_REVIEW_VERSION', self::version );
        define( 'BR_BOOK_REVIEW_FILE', __FILE__ );
        define( 'BR_BOOK_REVIEW_PATH', __DIR__ );
        define( 'BR_BOOK_REVIEW_URL', plugins_url( '', BR_BOOK_REVIEW_FILE ) );
        define( 'BR_BOOK_REVIEW_ASSETS', BR_BOOK_REVIEW_URL . '/assets' );
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'book_review_installed' );

        if ( !$installed ) {
            update_option( 'book_review_installed', time() );
        }
        update_option( 'book_review_version', BR_BOOK_REVIEW_VERSION );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        add_action( 'init', [$this, 'book_review_custom_post_type'] );
        add_action( 'add_meta_boxes', [$this, 'add_book_metx_boxes'] );
        add_action( 'save_post', [$this, 'book_meta_box_data_save'] );
        add_shortcode( 'sm_search_meta', [$this, 'sm_search_meta_field_data'] );
        add_action( 'init', [$this, 'create_book_custom_taxonomy'] );
    }

    /**
     * save the meta box field value with
     * meta key in DB.
     *
     * @since 1.0.0
     *
     * @param int $post_id The Post ID
     *
     * @return void
     */
    public function book_meta_box_data_save( $post_id ) {
        $book_author    = isset( $_POST['book_author'] ) ? sanitize_text_field( $_POST['book_author'] ) : '';
        $book_publisher = isset( $_POST['book_publisher'] ) ? sanitize_text_field( $_POST['book_publisher'] ) : '';
        $book_text      = isset( $_POST['book_text'] ) ? sanitize_text_field( $_POST['book_text'] ) : '';
        $book_isbn      = isset( $_POST['book_isbn'] ) ? sanitize_text_field( $_POST['book_isbn'] ) : '';
        $book_price     = isset( $_POST['book_price'] ) ? sanitize_text_field( $_POST['book_price'] ) : '';

        if ( !wp_verify_nonce( $_POST['book_nonce_action_field'], 'book_nonce_action' ) ) {
            return;
        }

        update_post_meta( $post_id, 'book_author', $book_author );
        update_post_meta( $post_id, 'book_publisher', $book_publisher );
        update_post_meta( $post_id, 'book_text', $book_text );
        update_post_meta( $post_id, 'book_isbn', $book_isbn );
        update_post_meta( $post_id, 'book_price', $book_price );
    }

    /**
     * book_meta_box_content function will
     * hold the HTML for the meta box
     *
     * @since 1.0
     *
     * @param WP_Post $post Post object.
     *
     * @return void
     */
    public function book_meta_box_content( $post ) {
        $book_author    = get_post_meta( $post->ID, 'book_author', true );
        $book_publisher = get_post_meta( $post->ID, 'book_publisher', true );
        $book_text      = get_post_meta( $post->ID, 'book_text', true );
        $book_isbn      = get_post_meta( $post->ID, 'book_isbn', true );
        $book_price     = get_post_meta( $post->ID, 'book_price', true );

        wp_nonce_field( 'book_nonce_action', 'book_nonce_action_field' );

        require_once __DIR__ . '/templates/search-box.php';
    }
    /**
     * adding a meta box to the custom post
     * type like book edit screen
     *
     * @since 1.0
     *
     * @return void
     */
    public function add_book_metx_boxes() {
        add_meta_box(
            'book_meta_box',
            __( 'Book Information', 'br_book_review' ),
            [$this, 'book_meta_box_content'],
            'book',
            'normal',
        );
    }

    /**
     * Creating a function to create a
     * custom post type Book
     *
     * @return void
     */
    public function book_review_custom_post_type() {
        //Set labels for Custom Post Type
        $labels = array(
            'name'               => __( 'Books', 'br_book_review' ),
            'singular_name'      => __( 'Book', 'br_book_review' ),
            'add_new'            => __( 'Add New', 'br_book_review' ),
            'add_new_item'       => __( 'Add New Book', 'br_book_review' ),
            'edit_item'          => __( 'Edit Book', 'br_book_review' ),
            'new_item'           => __( 'New Book', 'br_book_review' ),
            'all_items'          => __( 'All Book', 'br_book_review' ),
            'view_item'          => __( 'View Book', 'br_book_review' ),
            'search_items'       => __( 'Search Books', 'br_book_review' ),
            'not_found'          => __( 'No books found', 'br_book_review' ),
            'not_found_in_trash' => __( 'No books found in the Trash', 'br_book_review' ),
            'parent_item_colon'  => '',
            'menu_name'          => 'Books',
            'menu_icon'          => 'dashicons-welcome-write-blog',
        );
        // Set other options for Custom Post Type
        $args = array(
            'label'         => __( 'book', 'br_book_review' ),
            'labels'        => $labels,
            'description'   => __( 'Holds our books and book specific data', 'br_book_review' ),
            'public'        => true,
            'menu_position' => 5,
            'supports'      => array( 'title', 'editor', 'thumbnail' ),
            'has_archive'   => true,
            'taxonomies'    => array( 'category' ),
        );
        //creating a custom post type like book
        register_post_type( 'book', $args );
    }

    public function create_book_custom_taxonomy () {
        //Set labels for Custom Taxonomy
        $labels = array(
            'name'                       => _x( 'Book', 'Book General Name', 'search_meta' ),
            'singular_name'              => _x( 'Book', 'Book Singular Name', 'search_meta' ),
            'menu_name'                  => __( 'Book', 'search_meta' ),
            'all_items'                  => __( 'All Items', 'search_meta' ),
            'parent_item'                => __( 'Parent Item', 'search_meta' ),
            'parent_item_colon'          => __( 'Parent Item:', 'search_meta' ),
            'new_item_name'              => __( 'New Item Name', 'search_meta' ),
            'add_new_item'               => __( 'Add New Item', 'search_meta' ),
            'edit_item'                  => __( 'Edit Item', 'search_meta' ),
            'update_item'                => __( 'Update Item', 'search_meta' ),
            'view_item'                  => __( 'View Item', 'search_meta' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'search_meta' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'search_meta' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'search_meta' ),
            'popular_items'              => __( 'Popular Items', 'search_meta' ),
            'search_items'               => __( 'Search Items', 'search_meta' ),
            'not_found'                  => __( 'Not Found', 'search_meta' ),
            'no_terms'                   => __( 'No items', 'search_meta' ),
            'items_list'                 => __( 'Items list', 'search_meta' ),
            'items_list_navigation'      => __( 'Items list navigation', 'search_meta' ),
        );
        // Set other options for Custom Taxonomy
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy( 'taxonomy', array( 'book' ), $args );
    }

    /**
     * Search Data from post meta table
     * custom post type Book
     *
     * @return WP_Query Object
     */
    public function sm_search_meta_field_data( $atts ) {
        //load form html markup
        require_once __DIR__ . "/templates/search-box.php";

        if ( isset( $_GET["search"] ) ) {
            $search_value = sanitize_text_field(  ( $_GET["sm_search_meta"] ) );

            $meta_query_args = array(
                'post_type'  => 'book',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'book_author',
                        'value'   => $search_value,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => 'book_text',
                        'value'   => $search_value,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => 'book_publisher',
                        'value'   => $search_value,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => 'book_isbn',
                        'value'   => $search_value,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key'     => 'book_price',
                        'value'   => $search_value,
                        'compare' => 'LIKE',
                    ),
                ),
            );
            //store all post after search qurey
            $query = new WP_Query( $meta_query_args );

            if ( $query->have_posts() ) {
                echo '<div>';
                while ( $query->have_posts() ) {
                    $query->the_post();
                    echo '<span> ' . the_title() . '</span> </br>';
                    echo '<span>' . 'Book Author : ' . $query->post->book_author . '</span> </br>';
                    echo '<span>' . 'Book Published : ' . $query->post->book_publisher . '</span> </br>';
                    echo '<span>' . 'Book ISBN : ' . $query->post->book_isbn . '</span> </br>';
                    echo '<span>' . 'Book Price : ' . $query->post->book_price . '</span> </br>';
                    echo '<br/>';
                }
                echo '</div>';
            } else {
                // no posts found
            }
            /* Restore original Post Data */
            wp_reset_postdata();
        }
    }
}

/**
 * Initializes the main plugin
 *
 * @return \SearchMeta
 */
function search_meta() {
    return SearchMeta::init();
}

// kick-off the plugin
search_meta();
