<?php
namespace RelatedPost;

/**
 * Frontend class
 */
class Frontend {
    /**
     * Initilize class
     */
    public function __construct() {
        add_action( 'widgets_init', [$this, 'cf_register_widgets_cb'] );
    }

    public function cf_register_widgets_cb() {
        register_widget( new Public\Related_Post_Widgets() );
    }
}