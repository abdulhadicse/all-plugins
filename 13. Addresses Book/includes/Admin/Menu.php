<?php
namespace AddressBook\Admin;
/**
 * The Menu handler class
 */
class Menu {
    public $addressbook;

    /**
     * Initialize the class
     */
    public function __construct($addressbook) {
        $this->addressbook = $addressbook;
        add_action( 'admin_menu', [$this, 'create_admin_menu'] );
    }
    
    /**
     * Register admin menu
     *
     * @return void
     */
    public function create_admin_menu() {
        $parent_slug = 'address-books';
        $capablity   = 'manage_options';

        add_menu_page( __( 'Contact Book', 'address-book' ), __( 'Contact Book', 'address-book' ), 'manage_options', $parent_slug, [$this, 'address_book_page'] );
        add_submenu_page( $parent_slug, __( 'Address Book', 'address-book' ), __( 'Address Book', 'address_book' ), $capablity, $parent_slug, [$this, 'address_book_page'] );
        add_submenu_page( $parent_slug, __( 'Setting', 'adsress-book' ), __( 'Setting', 'address_book' ), $capablity, 'setting-page', [$this, 'setting_page'] );
    }

    public function address_book_page() {
        $this->addressbook->plugin_page();
    }

    /**
     * Handles the settings page
     *
     * @return void
     */
    public function setting_page() {
        echo 'Setting Page';
    }
}