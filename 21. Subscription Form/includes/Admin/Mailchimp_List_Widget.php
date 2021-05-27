<?php
namespace WPCFORM\Admin;

/**
 * Adds MailChimp List widget.
 */
class Mailchimp_List_Widget extends \WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'mail_chimp_list_widget',
            __( 'Mail Chimp List', 'wps-form' ),
            array( 'description' => __( 'A mail chimp list widgets', 'wps-form' ), )
        );
    }

    public function widget( $args, $instance ) {}

    /**
     * Back-end widget form.
     *
     *
     * @param array $instance
     */
    public function form( $instance ) {
        $get_key = get_option( 'wps_form_api_key' );
        $api_key = isset( $get_key ) ? sanitize_text_field( $get_key ) : '';
        
        $args = array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key )
            )
        );
        $url = 'https://us1.api.mailchimp.com/3.0/lists';

        $response       = wp_remote_get( $url, $args );
        $body           = wp_remote_retrieve_body( $response );
        $data           = json_decode( $body, true );

        $audience_key   = isset( $instance['mailchimp-list'] ) ? sanitize_text_field( $instance['mailchimp-list'] ) : '';
        $title          = isset( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : '';

        //html form
        include __DIR__ . '/views/mail-chimp-list.php';
    
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     *
     * @param array $new_instance 
     * @param array $old_instance 
     *
     * @return array $instance
     */
    public function update( $new_instance, $old_instance ) {
 
        $instance = array();
 
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['mailchimp-list'] = ( ! empty( $new_instance['mailchimp-list'] ) ) ? $new_instance['mailchimp-list'] : '';
 
        return $instance;
    }


}