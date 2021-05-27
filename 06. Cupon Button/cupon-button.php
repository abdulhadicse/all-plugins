<?php
/**
 * Display A Cupon Button Plugin
 *
 * @package           Cupon Button
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Cupon Button
 * Plugin URI:        https://www.github.com/abdulhadicse/all-plugins
 * Description:       A tutorial plugin for display a cupon button.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       cupon_button
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function add_before_my_siderbar( ) 
{
    if( is_front_page() || is_home() ){
?>

<div class="optin-coupon-section">
    <div class="optin-coupon-wrap">
            <div class="hwd">
                <div class="coupon-image" >
                    <img src= "<?php echo plugins_url('assets/img/side-ribbon-tag-img.svg', __FILE__) ?>"  alt="Coupon" width="30" height="30" />
                </div>
                
                <div class="coupon-text1" >
                    <span>Coupons</span> 
                </div>
            </div>

            <div class="hwd1">
                <div class="coupon-text1 " >
                    <span id="text-size-span">Need Any Help?</span> 
                </div>
            </div>
    </div>
</div>

<?php
    }
}

add_action( 'wp_footer', 'add_before_my_siderbar' );


function add_theme_scripts() {
    wp_enqueue_style( 'custom-style', plugins_url('assets/css/style.css', __FILE__) );
}

add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );
