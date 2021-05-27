<?php

namespace Github;
/**
 * Assets class
 */
class Assets{
    /**
     * Initialize class
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_style_scripts'] );
        
    }

    public function get_styles() {
        return [
            "github-jobs" => [
                "src" => GITHUB_JOBS_ASSETS . '/css/github-jobs.css' ,
                "version" => filemtime( GITHUB_JOBS_PATH . '/assets/css/github-jobs.css' ),
            ]

        ];
    }

    public function get_scripts() {
        return [
            "job-search" => [
                "src" => GITHUB_JOBS_ASSETS . '/js/job-search.js' ,
                "version" => filemtime( GITHUB_JOBS_PATH . '/assets/js/job-search.js' ),
                "deps" => array('jquery')
            ]

        ];
    }

    public function enqueue_style_scripts() {
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

        wp_localize_script( 'job-search', 'gj', [
            'url' => admin_url( 'admin-ajax.php' )
        ] );
    }
}