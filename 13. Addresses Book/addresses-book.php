<?php
/**
 * Address Book - Create New User
 *
 * @package           Adressess Book
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Address Book
 * Plugin URI:        https://wedevs.com
 * Description:       This plugin create a new user info and save data in db.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       address-book
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

use AddressBook\Admin\AddressBook;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ .'/vendor/autoload.php';

/**
 * The Main Plugin Class class
 */
final class Address_Book {
    /**
     * Plugin Version
     * 
     * @var String
     */
    const version = '1.0';

    public function __construct() {
        $this->define_constant();
        register_activation_hook( __FILE__, [$this, 'activate'] );
        add_action( 'plugins_loaded', [$this, 'init_plugins'] );
    }

    public function init_plugins () {
        
        if( is_admin() ) {
            new \AddressBook\Admin();
        }
        new \AddressBook\Asset();
    }

    public function activate () {
        $installer = new AddressBook\Installer();
        $installer->run();
    }
    
    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constant() {
        define( 'AB_ADDRESS_BOOK_VERSION', self::version );
        define( 'AB_ADDRESS_BOOK_FILE', __FILE__ );
        define( 'AB_ADDRESS_BOOK_PATH', __DIR__ );
        define( 'AB_ADDRESS_BOOK_URL', plugins_url( '', AB_ADDRESS_BOOK_FILE ) );
        define( 'AB_ADDBRESS_BOOK_ASSETS', AB_ADDRESS_BOOK_URL . '/assets' );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Address_Book
     */
    static function init() {
        static $instance = false;
        if ( !$instance ) {
            $instance = new self();
        }
        return $instance;
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Address_Book
 */
function address_book() {
    return Address_Book::init();
}

//kick-off
address_book();