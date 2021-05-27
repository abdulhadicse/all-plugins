<?php

namespace RelatedPost\Public;

/**
 * Add Related_Post_Widgets
 */
class Related_Post_Widgets extends \WP_Widget {

    /**
     * Register widget.
     */
    public function __construct() {
        parent::__construct(
            'related_post_widget',
            __('Related Post Widget', 'related-post'),
            array( 'description' => __( 'A demo related post widget that display five (5) related posts', 'related-post' ), ) 
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
        if ( is_singular( 'post' ) ) {
            extract( $args );
            $title = apply_filters( 'widget_title', 'Cat Fact Display' );
    
            echo $before_widget;
            if ( ! empty( $title ) ) {
                echo $before_title . $title . $after_title;
            }

            $post_id    = get_the_ID();
            $categories = get_the_category( $post_id );
            $cat_ids    = array();

            if(!empty($categories) && !is_wp_error($categories)):
                foreach ($categories as $category):
                    array_push($cat_ids, $category->term_id);
                endforeach;
            endif;

            $current_post_type = get_post_type($post_id);

            $args = array( 
                'category__in'   => $cat_ids,
                'post_type'      => $current_post_type,
                'post__not_in'    => array($post_id),
                'posts_per_page'  => '5',
             );

            $the_query = new \WP_Query( $args );

            if ( $the_query->have_posts() ) {
                echo '<ul>';
                while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        echo '<li>';
                        echo '<a href="#">' . get_the_title() .'</a>';
                        echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p> NO post found! </p>';
            }
            wp_reset_postdata();
            
            echo $after_widget;
        }
    } 
}