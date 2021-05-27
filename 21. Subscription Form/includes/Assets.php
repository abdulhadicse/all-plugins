<?php

namespace WPCForm;

/**
 * Assets Class
 */
class Assets{
    /**
     * Initialize Class
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'register_scripts'] );
    }

    public function get_styles() {
        return [
            'wps-form-css' => [
                'src'       => WPS_FORM_ASSETS . '/css/subscription-form.css',
                'version'   => filemtime( WPS_FORM_PATH . '/assets/css/subscription-form.css' ),
            ]
        ];
    }

    public function get_scripts() {
        return [
            'wps-form-js' => [
                'src'       => WPS_FORM_ASSETS . '/js/subscription-form.js',
                'version'   => filemtime( WPS_FORM_PATH . '/assets/js/subscription-form.js' ),
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

        wp_localize_script( 'wps-form-js', 'wps', [
            'url' => admin_url( 'admin-ajax.php' ),
        ]  );

    }
}