<?php

namespace WPCFORM;

/**
 * Admin class
 */
class Admin{
    /**
     * Initialize class
     */
    public function __construct() {
        add_action( 'admin_init', [ $this, 'register_setting_field' ] );
        add_action( 'widgets_init', [ $this, 'register_mail_chimp_list_widget' ] );
    }
    
    /**
     * Register MailChimp List widget
     *
     * @return void
     */
    public function register_mail_chimp_list_widget() {
        register_widget( new Admin\Mailchimp_List_Widget() ); 
    }

    /**
     * Register API Key Setting Field
     *
     * @return void
     */
    public function register_setting_field() {
        // Add the section to general settings
        add_settings_section(
            'wps_form_api_setting',
            __( 'WP Subscribe Form API Setting', 'wps-form' ),
            [ $this, 'wpsc_form_callback_function' ],
            'general'
        );

        // Add the field for set/collect api key
        add_settings_field(
            'wps_form_api_key',
            __( 'API Key', 'wps-form' ),
            [ $this, 'wps_api_key_callback_function' ],
            'general',
            'wps_form_api_setting'
        );

        register_setting( 'general', 'wps_form_api_key' );
    }

    /**
     * Settings section callback function
     *
     * @return void
     */
    public function wpsc_form_callback_function() {
        _e( ' Collect Your API Key From MainChimp Account ', 'wps-form');
    }

    /**
     * Callback function for api key setting field
     *
     * @return void
     */
    public function wps_api_key_callback_function() {
        $get_key = get_option( 'wps_form_api_key' );
        $api_key = isset( $get_key ) ? sanitize_text_field( $get_key ) : '';
        ?>

        <input 
            class="regular-text ltr" 
            type="text" 
            name="wps_form_api_key" 
            id="wps_form_api_key" 
            value="<?php echo esc_attr( $api_key ) ; ?>" 
        />

        <p class="description"> <?php _e( 'The Api key for connecting with your mailchimp account.' , 'wps-form') ?> <a href="https://mailchimp.com/">Get your API key here</a></p>

        <?php
    }
}