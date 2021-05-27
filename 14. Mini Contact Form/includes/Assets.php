<?php

namespace ContactForm;

/**
 * Assets Class
 */
class Assets{
    /**
     * Initialize Class
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'register_scripts'] );
        add_action( 'admin_enqueue_scripts', [$this, 'register_scripts'] );
    }

    public function get_styles() {
        return [
            'mcf-form-css' => [
                'src'       => MCF_FORM_ASSETS . '/css/mcf-form.css',
                'version'   => filemtime( MCF_FORM_PATH . '/assets/css/mcf-form.css' ),
            ]
        ];
    }

    public function get_scripts() {
        return [
            'mcf-form-js' => [
                'src'       => MCF_FORM_ASSETS . '/js/shortcode.js',
                'version'   => filemtime( MCF_FORM_PATH . '/assets/js/shortcode.js' ),
                'deps'      => array('jquery'),
            ]
        ];
    }

    public function register_scripts() {

        $styles = $this->get_styles();

        foreach( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        $scripts = $this->get_scripts();

        foreach( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;
            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        wp_localize_script( 'mcf-form-js', 'mcf', [
            'url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'mcf-contact-form' ),
        ]  );

    }
}