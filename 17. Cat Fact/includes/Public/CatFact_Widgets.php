<?php

namespace CATFACT\Public;

/**
 * Add CatFact_Widgets
 */
class CatFact_Widgets extends \WP_Widget {

    private $base_api = 'https://cat-fact.herokuapp.com/facts';

    /**
     * Register widget.
     */
    public function __construct() {
        parent::__construct(
            'cat_fact_widget',
            __('Cat Fact Widgets', 'cat-fact'),
            array( 'description' => __( 'A demo cat fact widgets that display five (5) posts', 'cat-fact' ), ) 
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args
     * @param array $instance
     * 
     * @return void
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', 'Cat Fact Display' );
 
        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        //load data from api 
        $data = $this->load_api_data();
        //display api post
        if( is_array( $data ) ) {
            foreach ($data as $key => $value) {
                echo '<h6>' . $key+1 . ') ' . $value['text'] .'</h6>';          
            }
        }
        echo $after_widget;
    }

    /**
     * Load data from api
     *
     * @return array $data api data
     */
    public function load_api_data() {
        $args = array(
            'timeout'     => '20',
        );

        $result = get_transient( 'cat_fact_results' );

        if( empty ( $result )) {
            $response = wp_remote_get( $this->base_api, $args );
            $body     = wp_remote_retrieve_body( $response );
            
            set_transient( 'cat_fact_results', $body, 24 * HOUR_IN_SECONDS );
        }
        
        $data     = json_decode( $result, true );
        return $data;
    }
}