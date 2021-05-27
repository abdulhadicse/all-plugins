<?php
/**
 * Email Notification Multiuser Plugin
 *
 * @package           Email Notification Multiuser
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Email Notification Multiuser
 * Plugin URI:        https://www.github.com/abdulhadicse/all-plugins
 * Description:       A tutorial plugin for email notification multiple user after a new post published. 
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       post_notification_multiuser
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
final class PEM_Post_Email_Multiuser {

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
        add_filter( 'add_multiple_user_email', [ $this, 'get_multiple_user_email'], 10, 1 );
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

            $multiple_user_email  = apply_filters( 'add_multiple_user_email', $to ); 
            
            foreach( $multiple_user_email as $email ) {
                wp_mail( $email, $subject, $message );
            }
        }          
    }
    /**
     * Call mutiple email method
     * with parameter admin email
     * @return Array
     */
    public function get_multiple_user_email( $email ) {
        $to = array(
            'hello@dolly.net',
            'weDevs@wordpress.com',
            'demomail@mail.co'
        );
        array_push($to, $email );

        return $to;
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

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'PE_MULTIUSER_VERSION', self::version );
        define( 'PE_MULTIUSER_FILE', __FILE__ );
        define( 'PE_MULTIUSER_PATH', __DIR__ );
        define( 'PE_MULTIUSER_URL', plugins_url( '', PE_MULTIUSER_FILE ) );
        define( 'PE_MULTIUSER_ASSETS', PE_MULTIUSER_URL . '/assets' );
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
        $installed = get_option( 'pe_multiuser_installed' );

        if ( ! $installed ) {
            update_option( 'pe_multiuser_installed', time() );
        }

        update_option( 'pe_multiuser_version', PE_MULTIUSER_VERSION );
    }        
}

/**
 * Initializes the main plugin
 *
 * @return \PEM_Post_Email_Multiuser
 */
function post_email_multiuser() {
    return PEM_Post_Email_Multiuser::init();
}

// kick-off the plugin
post_email_multiuser();
