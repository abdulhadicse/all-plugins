<?php
namespace AddressBook;

class Asset {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_assets'] );
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );
    }

    public function get_script() {
        return [
            'admin-script' => [
                'src'     => AB_ADDBRESS_BOOK_ASSETS . '/js/frontend.js',
                'version' => filemtime(AB_ADDRESS_BOOK_PATH . '/assets/js/frontend.js'),
            ],
        ];
    }

    public function get_style() {
        return [
            'admin-style' => [
                'src'     => AB_ADDBRESS_BOOK_ASSETS . '/css/frontend.css',
                'version' => filemtime(AB_ADDRESS_BOOK_PATH . '/assets/css/frontend.css'),
            ],
        ];

    }

    public function enqueue_assets() {
        $scripts = $this->get_script();
        $styles = $this->get_style();

        foreach($scripts as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : false;
            wp_register_script($handle, $script['src'], $deps, $script['version'], true);
        }

        foreach($styles as $handle => $style) {
            $deps = isset($script['deps']) ? $script['deps'] : false;
            wp_register_style($handle, $script['src'], $deps, $script['version']);
        }

        wp_enqueue_style('admin-style');
        wp_enqueue_script('admin-script');
    }

}