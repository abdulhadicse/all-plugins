<?php
/**
 * Insert a new address
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */
function ab_address_book_insert_data ( $args=[] ) {
    global $wpdb;

    $default = [
        'name' => '',
        'email' => '',
        'address' => '',
        'created_by' => get_current_user_id(),
        'created_at' => current_time('mysql'),
    ];

    $data = wp_parse_args( $args, $default );

    $inserted = $wpdb->insert(
      $wpdb->prefix . 'address_book',
      $data,
      [
          '%s',
          '%s',
          '%s',
          '%d',
          '%s'
      ]
    );

    if( ! $inserted ) {
        return new \WP_Error('failed-to-insert', __('Failed to insert data', 'address-book'));
    }

    return $wpdb->insert_id;
}

/**
 * Fetch Addresses
 *
 * @param  array  $args
 *
 * @return array
 */
function ab_address_book_get_data ( $args = []) {
    global $wpdb;

    $default = [
        'number' => '20',
        'offset' => '0',
        'orderby' => 'id',
        'order' => 'ASC'
    ];

    $data = wp_parse_args($args, $default);

    $sql = $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}address_book
        ORDER BY {$data['orderby']} {$data['order']}
        LIMIT %d, %d",
        $data['offset'], $data['number']
    );

    $items = $wpdb->get_results($sql);

    return $items;
}

/**
 * Get the count of total address
 *
 * @return int
 */
function ab_address_book_get_count () {
    global $wpdb;
    return (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}address_book" );
}

/**
 * Fetch a single contact from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function ab_address_book_get_address( $id ) {
    global $wpdb;

    return $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}address_book WHERE id = %d", $id )
    );
}

/**
 * Delete an address
 *
 * @param  int $id
 *
 * @return int|boolean
 */
function ab_address_book_delete_address( $id ) {
    global $wpdb;

    return $wpdb->delete(
        $wpdb->prefix . 'address_book',
        [ 'id' => $id ],
        [ '%d' ]
    );
}