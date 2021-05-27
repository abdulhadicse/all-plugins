<?php
/**
 * Featured Posts plugin that display some post using shortcode handle by user.
 *
 * @package           Featured Posts
 * @author            Abdul Hadi <abdul.hadi.aust@gmail.com>
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Featured Posts
 * Plugin URI:        https://github.com/abdulhadicse
 * Description:       This is a featured posts plugin that display some post using shortcode handle by user..
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       featured-posts
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * Copyright (c) 2021 Abdul Hadi (email: abdul.hadi.aust@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

 // don't call the file directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Main Class
 */
final class Featured_Posts{
    /**
     * Plugin version
     */
    const version = '1.0.0';
   
    /**
     * our constructor 
     * call initial hook
     *
     * @return void
     */
    private function __construct() {
        $this-> define_constant();
        register_activation_hook( __FILE__, [$this, 'fp_active'] );
        add_action( 'plugins_loaded', [$this, 'init_plugins'] );
    }

    /**
     * Register display number of post setting field
     *
     * @return void
     */
    public function fp_number_of_posts_cb(){
        $no_of_posts =  get_option( 'fp_feature_post_info' );
        ?>
            <input 
                type="text" 
                class="" 
                name="fp_feature_post_info[no_of_posts]" 
                placeholder="Display number of posts" 
                value="<?php echo isset( $no_of_posts['no_of_posts'] ) ? $no_of_posts['no_of_posts'] : '' ?>"
            >
        <?php
    }
    
    /**
     * Register add post order option setting field
     *
     * @return void
     */
    public function fp_post_order_cb(){
        $order_of_posts =  get_option( 'fp_feature_post_info' );
        ?>
            <select name="fp_feature_post_info[order_of_posts]" id="cars">
                <option value="asc"  <?php echo isset( $order_of_posts['order_of_posts'] ) ? ( selected( $order_of_posts['order_of_posts'], 'asc', false ) ) : ( '' ); ?>> <?php esc_html_e( 'ASC', 'feature-posts' ); ?> </option>
                <option value="desc"  <?php echo isset( $order_of_posts['order_of_posts'] ) ? ( selected( $order_of_posts['order_of_posts'], 'desc', false ) ) : ( '' ); ?> > <?php esc_html_e( 'DESC', 'feature-posts' ); ?> </option>
                <option value="random"  <?php echo isset( $order_of_posts['order_of_posts'] ) ? ( selected( $order_of_posts['order_of_posts'], 'random', false ) ) : ( '' ); ?> > <?php esc_html_e( 'Random', 'feature-posts' ); ?> </option>
            </select>
        <?php
    }

    /**
     * Register post category setting field
     *
     * @return void
     */
    public function fp_post_categories_cb(){
        $categories = get_categories();
        $all_categories = [];
        
        //extact single category
        foreach( $categories as $category ) {
            $all_categories[] = $category->name;   
        }

        $post_category =  get_option( 'fp_feature_post_info' );
        $select_category_array = isset( $post_category['category'] ) ? $post_category['category'] : [];

        //cecked which category is selectd
        foreach ( $all_categories as $category ) {
			$_category       = ucwords( $category );
            $checked         = in_array( $_category, $select_category_array ) ? 'checked' : '';
        ?>
        <input type="checkbox" name="fp_feature_post_info[category][]" id="" value="<?php echo $_category; ?>" <?php echo $checked; ?> />
        <label for=""> <?php echo $_category;  ?>  </label> <br>
		<?php }
    }

    /**
     * Register a setting section in General Setting Page
     *
     * @return void
     */
    public function fp_register_custom_settings_field_cb() {
        register_setting( 'general', 'fp_feature_post_info', [ 'type' => 'array' ] );
        add_settings_section( 'feature-posts', __('Feature Posts Settings', 'feature-posts'), [$this, 'feature_posts_setting_cb'], 'general' );
        add_settings_field( 'fp_no_of_posts', __('Numbers of Posts', 'feature-posts'), [$this, 'fp_number_of_posts_cb'], 'general','feature-posts');
        add_settings_field( 'fp_post_order', __('Post Order', 'feature-posts'), [$this, 'fp_post_order_cb'], 'general', 'feature-posts');
        add_settings_field( 'fp_post_categories', __('Post Categories', 'feature-posts'), [$this, 'fp_post_categories_cb'], 'general', 'feature-posts');
    }

    /**
     * Call Initial Plugin
     *
     * @return void
     */
    public function init_plugins() {
        if( is_admin() ) {
            add_action( 'admin_init', [$this, 'fp_register_custom_settings_field_cb'] );
        }
        add_shortcode( 'feature-posts', [$this,'feature_posts_shortcode_cb'] );
    }

    /**
     * Custom shortcode display Posts
     *
     * @return Array
     */
    public function feature_posts_shortcode_cb() {

        $feature_posts =  get_option( 'fp_feature_post_info' );
        $no_of_posts = $feature_posts['no_of_posts'];
        $order_of_posts = $feature_posts['order_of_posts'];
        $selected_category = isset( $feature_posts['category'] ) ? implode( ",", $feature_posts['category'] ) : '';

        $atts = [
            'posts'  => $no_of_posts,
            'order'  => $order_of_posts,
            'select' => $selected_category 
        ];

        if( empty($no_of_posts) ){
            unset($atts['posts']);
        }

        if( empty($selected_category) ){
            unset($atts['select']);
        }

        if($order_of_posts == 'random'){
            $atts['orderby'] = 'rand';
        }
        
        $a = shortcode_atts( array(
            'posts'     => '10',
            'order'     => 'ASC',
            'select'    => 'Uncategorized',
            'orderby'   => '',
        ), $atts );
    
        $args = array(
            'post_type'        => 'post',
            'order'            => $a['order'],
            'posts_per_page'   => $a['posts'],
            'category_name'    => $a['select'],
            'orderby'          => $a['orderby']
        );
        // The Query

        $the_query = get_transient( 'feature_post_transient_data' );

        if( false == $the_query ) {
            $the_query = new WP_Query( $args );
            set_transient( 'feature_post_transient_data', $the_query, MINUTE_IN_SECONDS );
        }
        
        
        // The Loop
        if ( $the_query->have_posts() ) {
            echo '<ul>';
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                echo '<li>' . get_the_title() . '</li>';
            }
            echo '</ul>';
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();
    }

    /**
     * Setting field call back
     */
    public function feature_posts_setting_cb() {
        echo __('Featured Posts under Settings menu. Customize your featured posts.', 'featured-posts');
    }
    /**
     * Store Info Install Plugin
     *
     * @return void
     */
    public function fp_active() {
        $install = get_option( 'FP_Featured_Posts_install' );
        if( ! $install ) {
            update_option( 'FP_Featured_Posts_install', time() );
        }
        update_option( 'FP_Featured_Posts_version', FP_VERSION );
    }
    /**
     * Create a signleton instance
     * of our main class
     *
     * @return \Featured_Posts
     */
    public static function init() {
        static $instance = false;
        if( ! $instance ) {
            $instance = new self();
        }
        return $instance;
    }
    /**
     * Define require plugin constant
     *
     * @return void
     */
    public function define_constant() {
        define( 'FP_VERSION', self::version );
        define( 'FP_FILE', __FILE__ );
        define( 'FP_PATH', __DIR__ );
        define( 'FP_URL', plugins_url( '', FP_FILE ) );
        define( 'FP_ASSETS', FP_URL . '/assets' );
    }
}
/**
 * Call main class
 *
 * @return \Featured_Posts
 */
function featured_posts() {
    return Featured_Posts::init();
}
//kick-of
featured_posts();