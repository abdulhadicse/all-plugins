<?php
namespace AddressBook\Admin;
use AddressBook\Traits\Form_Error;

class AddressBook {
    use Form_Error;
    /**
     * Plugin page handler
     *
     * @return void
     */
    public function plugin_page () {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';

        switch ( $action ) {
            case 'new':
                $template = __DIR__ . '/views/add-new-address.php';
                break;

            case 'edit':
                $template = __DIR__ . '/views/address-edit.php';
                break;

            case 'view':
                $template = __DIR__ . '/views/address-view.php';
                break;

            default:
                $template = __DIR__ . '/views/list-address.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }
    
    /**
     * Handle the form
     *
     * @return void
     */
    public function form_handler() {
        if( ! isset( $_POST['submit_address'] ) ) {
            return;
        }

        if( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }

        if( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-address' ) ) {
            wp_die( 'Are you cheating?' );
        }

        $name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
        $email = isset ( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
        $address = isset( $_POST['address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address'] ) ) : '';

        if ( empty( $name ) ) {
            $this->errors['name'] = __(' You must provide a name', 'address-book' );
        }

        if ( empty( $email ) ) {
            $this->errors['email'] = __(' You must provide a email', 'address-book' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        $insert_id = ab_address_book_insert_data( [
            'name' => $name,
            'email' => $email,
            'address' => $address
        ] );

        if ( is_wp_error( $insert_id ) ) {
            wp_die( $insert_id->get_error_message() );
        }

        $redirected_to = admin_url( 'admin.php?page=address-books&inserted=true' );
        wp_redirect( $redirected_to );
        exit;
    }
}