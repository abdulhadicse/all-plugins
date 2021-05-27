<?php
namespace DLP;

/**
 * Frontend class
 */
class Frontend{
    /**
     * Initialize class
     */
    public function __construct() {
        add_action( 'widgets_init', [$this, 'dlp_register_widgets_cb'] );
    }

    /**
     * Register Latest Post Widgets
     *
     * @return void
     */
    public function dlp_register_widgets_cb() {
        register_widget( new Public\Latest_Post_Widget() );
    }
}