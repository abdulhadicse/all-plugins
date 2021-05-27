<?php
/**
 * Post Email Notification
 *
 * @package           Post Email Notification
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Post Email Notification
 * Plugin URI:        https://www.github.com/abdulhadicse/all-plugins
 * Description:       A tutorial plugin for email notification after a new post published.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       post_notification
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
final class PE_Post_Email {

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
        add_action( 'transition_post_status', [$this, 'get_author_publish_notice'],10, 3);
    }

    /**
     * Call author published notice hook
     * 
     * @return String
     */
    public function get_author_publish_notice( $new_status, $old_status, $post ) {
        //Check existing Post or not
        if ( ( 'publish' === $new_status && 'publish' !== $old_status ) ) { 
            $post_title     = $post->post_title; 
            $to             = get_option('admin_email');
            $subject        = 'Post publish notification';
            $message        = $post_title;

            wp_mail($to, $subject, $message );           
        }          
    }

    /**
     * Initializes a singleton instance
     *
     * @return \PE_Post_Email
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
        define( 'POST_EMAIL_VERSION', self::version );
        define( 'POST_EMAIL_FILE', __FILE__ );
        define( 'POST_EMAIL_PATH', __DIR__ );
        define( 'POST_EMAIL_URL', plugins_url( '', POST_EMAIL_FILE ) );
        define( 'POST_EMAIL_ASSETS', POST_EMAIL_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {

    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'post_email_installed' );

        if ( ! $installed ) {
            update_option( 'post_email_installed', time() );
        }

        update_option( 'post_email_version', POST_EMAIL_VERSION );
    } 
}

/**
 * Initializes the main plugin
 *
 * @return \PE_Post_Email
 */
function pe_post_email() {
    return PE_Post_Email::init();
}

// kick-off the plugin
pe_post_email();
