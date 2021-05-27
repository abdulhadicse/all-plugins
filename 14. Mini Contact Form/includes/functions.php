<?php

/**
 * inserted data
 *
 * @param array $args
 * @return id  Inserted data id
 */
function mcf_contact_form_insert_data( $args = [] ) {
    global $wpdb;

    $default = [
        'name'          => '',
        'email'         => '',
        'phone'         => '',
        'site'          => '',
        'message'       => '',
        'created_at'  => current_time( 'mysql' ),
    ];

    $data = wp_parse_args( $args, $default );

    $insert = $wpdb->insert(
        $wpdb->prefix.'contact_form',
        $data,
        [
            '%s',
            '%s',
            '%d',
            '%s',
            '%s'
        ]
    );

    if ( ! $insert ) {
        return new \WP_Error ( 'failed-to-insert', __('Failed to insert', 'mcf-form') );
    }

    return $wpdb->insert_id;
}

function mcf_contact_form_select_data() {
    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}contact_form";
    $data = $wpdb->get_results( $sql );
}