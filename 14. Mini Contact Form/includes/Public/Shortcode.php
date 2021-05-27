<?php

namespace ContactForm\Public;

/**
 * Shortcode class
 */
class Shortcode{
    /**
     * Initialize class
     */
    public function __construct() {
        add_shortcode( 'mcf-contact-form', [$this, 'render_contact_form'] );
        add_action( 'wp_ajax_nopriv_mcf_form', [$this,'mcf_handle_form'] );
        add_action( 'wp_ajax_mcf_form', [$this,'mcf_handle_form'] );
    }

    public function render_contact_form(){
        wp_enqueue_style( 'mcf-form-css' );
        wp_enqueue_script( 'mcf-form-js' );
        
        
        ob_start();
        include __DIR__ . '/views/mcf-form.php';
        $data = ob_get_clean();
        return $data;
    }

    public function mcf_handle_form() {
        
        $name       = isset( $_REQUEST['name'] ) ? sanitize_text_field( $_REQUEST['name'] ) : '';
        $email      = isset( $_REQUEST['email'] ) ? sanitize_text_field( $_REQUEST['email'] ) : '';
        $phone      = isset( $_REQUEST['phone'] ) ? sanitize_text_field( $_REQUEST['phone'] ) : '';
        $site       = isset( $_REQUEST['site'] ) ? sanitize_text_field( $_REQUEST['site'] ) : '';
        $message    = isset( $_REQUEST['message'] ) ? sanitize_text_field( $_REQUEST['message'] ) : '';

        $default = [
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone,
            'site'          => $site,
            'message'       => $message,
        ];

        $id = mcf_contact_form_insert_data( $default );

        error_log( $id );
    }
}