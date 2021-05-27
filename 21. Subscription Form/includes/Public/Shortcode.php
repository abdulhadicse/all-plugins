<?php

namespace WPCFORM\Public;
/**
 * Shortcode class
 */
class Shortcode {
    /**
     * Initialize class
     */
    public function __construct() {
        add_shortcode( 'wps-form', [ $this, 'render_subscription_form' ] );
        add_action( 'wp_ajax_wps_form_submission', [ $this, 'wps_form_ajax_handle' ] );
        add_action( 'wp_ajax_nopriv_wps_form_submission', [ $this, 'wps_form_ajax_handle' ] );
    }
    /**
     * [wps-form] shortcode callback
     *
     * @return void
     */
    public function render_subscription_form( ) {
        wp_enqueue_style( 'wps-form-css' );
        wp_enqueue_script( 'wps-form-js' );

        $get_title       = get_option( 'widget_mail_chimp_list_widget' );
        $title           = isset( $get_title ) ? sanitize_text_field( $get_title['2']['title'] ) : __('Subscribe to our Newsletter', 'wps-form');

        
        ob_start();
        include __DIR__ . '/views/subscription-form.php';
        $result = ob_get_clean();

        return $result;
    }

    /**
     * [wps-form] Shortcode Ajax Form Handle
     *
     * @return void
     */
    public function wps_form_ajax_handle() {
        $email              = isset( $_POST['mail'] ) ? $_POST['mail'] : '' ;
        $_nonce             = $_REQUEST['_wpnonce'];
        $get_key            = get_option( 'wps_form_api_key' );
        $get_audience_key   = get_option( 'widget_mail_chimp_list_widget' );

        $api_key            = isset( $get_key ) ? sanitize_text_field( $get_key ) : '';
        $audience_key       = isset( $get_audience_key ) ? sanitize_text_field( $get_audience_key['2']['mailchimp-list'] ) : '';
        
        if ( empty( $email ) ) {
            return;
        }

        if (! wp_verify_nonce($_nonce, 'wps_form_action' ) ) {
            return;
        }

        if ( !empty ( $api_key ) && !empty ( $audience_key ) ) {
            $args = array(
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key ),
                    'Content-Type'  => 'application/json;charset=utf-8'
                ),
                'body' => json_encode( array (
                    'email_address' => $email,
                    'status'        => 'subscribed'
                ) )
            );

            $response = wp_remote_post( 'https://us1.api.mailchimp.com/3.0/lists/'.$audience_key.'/members/', $args ) ;
            $http_code = wp_remote_retrieve_response_code( $response );

            if ( 200 ==  $http_code ) {
                wp_send_json_success( 'Thanks for subscribe' );
            }
            else {
                wp_send_json_success( 'Try Again! Something went worng' );
            }
        }
    }
}