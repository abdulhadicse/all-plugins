<?php
/**
 * Book Review Custom Post Type With Metabox
 *
 * @package           Book Review
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Book Review
 * Plugin URI:        https://wedevs.com
 * Description:       This plugin create a custom post type like book review with some extra meta box features.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       br_book_review
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
final class BR_Book_Review {
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
        ?>

    <div class="container">
        <form action="">
            <label for="fname"><?php echo __( 'Book Author', 'br_book_review' ) ?></label>
            <input style="margin-bottom:5px" type="text" class="widefat" id="fname" name="book_author" value="<?php echo esc_html( $book_author ) ?>" placeholder="Enter Book Author Name">
            <br/>
            <label for="lname"><?php echo __( 'Book Publisher Date', 'br_book_review' ) ?></label>
            <input type="date" style="margin-bottom:5px" class="widefat" id="lname" name="book_publisher" value="<?php echo esc_html( $book_publisher ) ?>" placeholder="Enter Book Publisher Date">
            <br/>
            <label for="subject"><?php echo __( 'ISBN for Book', 'br_book_review' ) ?></label>
            <input type="text" style="margin-bottom:5px" class="widefat" id="lname" name="book_isbn" value="<?php echo esc_html( $book_isbn ) ?>" placeholder="Enter Book ISBN Number">
            <br/>
            <label for="subject"><?php echo __( 'Book Price', 'br_book_review' ) ?></label>
            <input type="text"  style="margin-bottom:5px" class="widefat" id="lname" name="book_price" value="<?php echo esc_html( $book_price ) ?>"  placeholder="Enter Book Price">
            <br/>
            <label for="book_text"><?php echo __( 'Personal Note', 'br_book_review' ) ?></label>
            <textarea id="book_text" name="book_text" rows="4" cols="124"><?php echo esc_html( $book_text ); ?></textarea>
        </form>
    </div>

    <?php
    }
    /**
     * adding a meta box to the custom post
     * edit screen
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
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        add_action( 'init', [$this, 'book_review_custom_post_type'] );
        add_action( 'add_meta_boxes', [$this, 'add_book_metx_boxes'] );
        add_action( 'save_post', [$this, 'book_meta_box_data_save'] );
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
}

/**
 * Initializes the main plugin
 *
 * @return \BR_Book_Review
 */
function br_book_review() {
    return BR_Book_Review::init();
}

// kick-off the plugin
br_book_review();
