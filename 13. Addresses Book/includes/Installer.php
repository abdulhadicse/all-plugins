<?php
namespace AddressBook;
/**
 * Installer class
 */
class Installer{
    /**
     * Run the installer
     *
     * @return void
     */
    public function run () {
        $this->add_version();
        $this->create_db();
    }

    /**
     * Add time and version on DB
     */
    public function add_version () {
        $installed = get_option( 'wd_academy_installed' );

        if ( ! $installed ) {
            update_option( 'wd_academy_installed', time() );
        }

        update_option( 'wd_academy_version', '1.0.0' );
    }

    /**
     * Create necessary database tables
     *
     * @return void
     */
    public function create_db (){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}address_book` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL DEFAULT '',
            `email` varchar(255) DEFAULT NULL,
            `address` varchar(255) DEFAULT NULL,
            `created_by` bigint unsigned NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate";

        if( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta($schema);
    }
}