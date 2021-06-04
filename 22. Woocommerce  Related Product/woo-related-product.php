<?php
/**
 * Plugin Name: Woo Related Product
 * Description: A plugin that display related product
 * Plugin URI: https://www.github.com/abdulhadicse/visitor-tracker/
 * Author: Abdul Hadi
 * Author URI: https://www.abdulhadi.info
 * Version: 1.0
 * License: GPL2 or later
 * Text Domain: woo-related-product
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The main plugin class
 */
final class Woo_Related_Product {
    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
     * Class construcotr
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Visitors_Tracker
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WOO_RELATED_PRODUCT_VERSION', self::version );
        define( 'WOO_RELATED_PRODUCT_FILE', __FILE__ );
        define( 'WOO_RELATED_PRODUCT_PATH', __DIR__ );
        define( 'WOO_RELATED_PRODUCT_URL', plugins_url( '', WOO_RELATED_PRODUCT_FILE ) );
        define( 'WOO_RELATED_PRODUCT_ASSETS', WOO_RELATED_PRODUCT_URL . '/assets' );
    }

    /**
     * Initialize plugin for localization
     *
     * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( 'woo-related-product', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }


    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        add_filter( 'woocommerce_product_tabs', [ $this, 'add_woocommerce_related_product_tabs' ] );
        add_action( 'woocommerce_before_shop_loop_item_title', [ $this, 'product_short_description' ], 20 );
        add_action( 'template_redirect', [ $this, 'add_product_to_cart' ] );
    }
    
    /**
     * Adding a new tabs [Related Post]
     *
     * @param array $tabs
     * @return array $tabs
     */
    public function add_woocommerce_related_product_tabs( $tabs ) {
        $tabs['woo_related_product'] = array(
            'title' => 'Woo Related Product',
            'priority' => 10,
            'callback' => [$this, 'woo_related_product' ]
        );
        
        return $tabs;
    }

    /**
     * Display related post
     *
     * @return void
     */
    public function woo_related_product() {
        $product_id       =  wc_get_product()->get_id();
        $related_products =  wc_get_related_products( $product_id );
        
        wc_set_loop_prop( 'name', 'woo_related_product' );
        
        if( $related_products ) {
            woocommerce_product_loop_start();
            foreach ( $related_products as $related_product ) {
                $post_object = get_post( $related_product );
                setup_postdata( $GLOBALS['post'] =& $post_object );
                
                wc_get_template_part( 'content', 'product' );
            }
            woocommerce_product_loop_end();
        } else {
            echo __( 'No related post' , 'woo-related-post' );
        }
    }

    /**
     * display short description after thumbnail
     *
     * @return void
     */
    public function product_short_description() {
        global $product, $woocommerce_loop;

        if( is_product() && $woocommerce_loop['name'] == 'woo_related_product' ) {
            echo $product->get_short_description();
        } 
    }
    /**
     * Add to cart product
     *
     * @return void
     */
    public function add_product_to_cart() {
        if ( is_product() ) {
            $product_id =  wc_get_product()->get_id();
            $found      = false;
            //check if product already in cart
            if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
                foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                    $_product = $values['data'];
                    if ( $_product->get_id() == $product_id )
                        $found = true;
                }
                // if product not found, add it
                if ( ! $found )
                    WC()->cart->add_to_cart( $product_id );
            } else {
                // if no products in cart, add it
                WC()->cart->add_to_cart( $product_id );
            }
        }
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'woo_related_product_installed' );

        if ( ! $installed ) {
            update_option( 'woo_related_product_installed', time() );
        }

        update_option( 'woo_related_product_version', WOO_RELATED_PRODUCT_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Woo_Related_Product
 */
function woo_related_product() {
    return Woo_Related_Product::init();
}

// kick-off the plugin
woo_related_product();