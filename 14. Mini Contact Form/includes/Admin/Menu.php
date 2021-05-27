<?php

namespace ContactForm\Admin;

/**
 * Menu Class
 */
class Menu{
    /**
     * Initialize the class
     */
    public function __construct() {
        add_action( 'admin_menu', [$this,'register_contact_from_menu'] );
    }

    public function register_contact_from_menu() {
        add_menu_page( 'mcf-form', __('Mini Contact Form', 'mcf-form'), 'manage_options', 'mini-contact-form', [$this, 'menu_page_contact_form_cb'], 'dashicons-list-view' );
    }

    public function menu_page_contact_form_cb() {
        echo '<h1>Mini Contact form</h1>';
    }
}