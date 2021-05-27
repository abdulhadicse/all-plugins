<?php
namespace AddressBook;

/**
 * The admin class
 */
class Admin {
    /**
     * Initialize the class
     */
    public function __construct() {
        $addressbook = new Admin\AddressBook;
        $this->define_handler( $addressbook );
        new Admin\Menu( $addressbook );
    }
    
    /**
     * Dispatch and bind actions
     *
     * @return void
     */
    public function define_handler ( $addressbook ) {
        add_action( 'admin_init', [$addressbook, 'form_handler'] );
    }
}