<?php
namespace DLP\Public;

/**
 * Latest Post Wigdet class
 */
class Latest_Post_Widget extends \WP_Widget{
    /**
     * Register widget
     */
    public function __construct() {
        parent::__construct(
            'dlp_latest_post',
            __( 'Display Latest Posts', 'latest-post' ),
            array( 'description' => __( 'Display Latest Posts', 'latest-post' ), )
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
        
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $before_widget;
        if( !empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $number_of_posts    = $instance['no-of-posts'] ;
        $order              = $instance[ 'post-order' ] ; 
        $category           = implode( "," , $instance[ 'category-list' ] ) ;

        $defaults = array (
            'posts_per_page' => 5,
            'order' => 'ASC',
            'category_name' => 'Uncategorized'
        );

        $args = array (
            'posts_per_page' => $number_of_posts,
            'order' => $order,
            'category_name' => $category
        );

        $args = wp_parse_args( $args, $defaults );

        $the_query = new \WP_Query( $args );

        if ( $the_query->have_posts() ) {
            echo '<ul>';
            while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    echo '<li>' . get_the_title() . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p> NO post found! </p>';
        }
        wp_reset_postdata();

        echo $after_widget;
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance.
     * 
     * @return void
     */
    public function form( $instance ) {
        //get all category
        $categories = get_categories();
        $category_list = [];
        foreach( $categories as $category ) {
            $category_list[] = $category->name;
        }

        $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
        $no_of_posts = isset( $instance[ 'no-of-posts' ] ) ? $instance[ 'no-of-posts' ] : '';
        $order = isset( $instance[ 'post-order' ] ) ? $instance[ 'post-order' ] : '';
        $category = isset( $instance[ 'category-list' ] ) ? $instance[ 'category-list' ] : [];
        
        include __DIR__ . '/views/latest-post-fields.php';
    }

    /**
     * Sanitize Widgets Fields
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return void
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        
        if( is_string ( $new_instance['title'] ) && ! is_numeric( $new_instance['title'] ) ) {
            $instance['title'] = sanitize_text_field( $new_instance['title'] );
        }

        if( is_numeric( $new_instance['no-of-posts'] ) ) {
            $instance['no-of-posts'] = sanitize_text_field( $new_instance['no-of-posts'] );
        }

        $instance['post-order'] = $new_instance['post-order'];
        $instance['category-list'] = $new_instance['category-list'];

        return $instance;
    }
}