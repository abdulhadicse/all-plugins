<?php

namespace ContactForm;

/**
 * Installer Class
 */
class Installer{
    /**
     * Initialize class
     *
     * @return void
     */
    public function run() {
        $this-> add_version();
        $this-> mcf_create_table();
    }

    /**
     * Store Plugin necessary documents
     *
     * @return void
     */
    public function add_version() {
        $installed = get_option( 'mcf_form_installed' );

        if ( ! $installed ) {
            update_option( 'mcf_form_installed', time() );
        }

        update_option( 'mcf_form_version', MCF_FORM_VERSION );
    }

    /**
     * Create Table Contact Form 
     *
     * @return void
     */
    public function mcf_create_table() {
        global $wpdb;

        $charset = $wpdb->get_charset_collate();

        $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}contact_form` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL DEFAULT '',
            `email` varchar(30) DEFAULT NULL,
            `phone` varchar(30) DEFAULT NULL,
            `site` varchar(30) DEFAULT NULL,
            `message` varchar(255) DEFAULT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset";

        if( ! function_exists ( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }
}


