<?php
/**
 * Contact From Shortcode Plugin
 *
 * @package           CF Shortcode
 * @author            Abdul Hadi
 * @copyright         2021 Abdul Hadi
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       CF Shortcode
 * Plugin URI:        https://www.github.com/abdulhadicse/all-plugins
 * Description:       A tutorial plugin for display a static contact form.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdul Hadi
 * Author URI:        http://abdulhadi.info
 * Text Domain:       cf_shortcode
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The main plugin class
 */
final class CF_Shortcode {

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
     * call a shortcode method 
     * to build a contact from.
     *  
     * @param mixed 
     *   
     * @return Array
     */
    public function wp_contact_plugin ( $attr, $content = null ) {
        $attr = shortcode_atts( array(
            'title'       => 'This is a Contact Form',
            'description' => 'a simple contact for getting user opinion',
        ), $attr );
        
        ob_start();
        ?>
        <div>
            <h2> <?php echo $attr['title'] ?> </h2>
            <h3> <?php echo $attr['description'] ?> </h3>
            <form action="" method="post">
                <?php echo do_shortcode( $content ) ?>
            </form>
        </div>
        <?php
        $data = ob_get_clean();

        return $data;
    }

    /**
     * call a shortcode method which give user
     * to build a contact from input field.
     *  
     * @param $attr
     * @param $content
     *   
     * @return Array
     */
    public function wedev_text_input_form( $attr, $content = null ) {
        $attr = shortcode_atts( array(
            'type'          => 'text',
            'name'          => 'name',
            'label'         => '',
            'placeholder'   => 'Placeholder Text',
            'value'         => 'name'
        ), $attr );
        
        //chech type casting because if user 
        //give worng input type
        switch ($attr['type']) {
            case 'button' :
                $attr['type'] = 'button';
               break;
            case 'checkbox' :
                $attr['type'] = 'checkbox';
               break;
            case 'radio' :
                $attr['type'] = 'radio';
               break;   
            case 'number' :
                $attr['type'] = 'number';
                break;
            case 'textarea' :
                $attr['file'] = 'file';
                break;
            case 'submit' :
                $attr['type'] = 'submit';
                break;
            default :
                $attr['type'] = 'text';
                break;
        }
        ob_start();
        ?>
        <div>
            <label for="fname"> <?php echo $attr['label'] ?> </label>
            <input type="<?php echo $attr['type'] ?>" name="<?php $attr['name'] ?>" placeholder="<?php $attr['placeholder'] ?>" value="<?php echo $attr['value'] ?>">
        </div>
        <?php
        $data = ob_get_clean();

        return $data;
    }

    /**
     * call a shortcode method which give user
     * to build a text area field.
     *  
     * @param $attr
     * @param $content
     *   
     * @return Array
     */
    public function wedev_textarea_input_form( $attr, $content = null ) {
        $attr = shortcode_atts( array(
            'type'          => 'textarea',
            'name'          => 'name',
            'label'         => '',
            'row'           => '4',
            'col'           => '50'
        ), $attr );
        
        ob_start();
        ?>
        <div>
            <label> <?php echo $attr['label'] ?> </label>
            <textarea name="<?php echo $attr['name'] ?>" rows="<?php echo $attr['row'] ?>" cols="<?php echo $attr['col'] ?>">
            </textarea>
        </div>
        <?php
        $data = ob_get_clean();

        return $data;
    }

    /**
     * call a shortcode method which give user
     * to build a text area field.
     *  
     * @param $attr
     * @param $content
     *   
     * @return Array
     */
    public function wedev_select_input_form( $attr, $content = null ) {
        $attr = shortcode_atts( array(
            'type'          => 'select',
            'name'          => 'name',
            'label'         => '',
            'options'           => 'Select Option',
        ), $attr );

        $options               = $attr['options'];
        //convert string options into array
        $get_options_in_array = explode(',', $options);

        ob_start();
        ?>
        <div>
            <label> <?php echo $attr['label'] ?> </label>
            <select>
                <?php 
                    foreach ( $get_options_in_array as $at => $val ) {
                        ?>
                            <option value="<?php echo $at; ?>"><?php echo $val; ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>
        <?php
        $data = ob_get_clean();

        return $data;
    }

    /**
     * Initializes a singleton instance
     *
     * @return \CF_Shortcode
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
        define( 'WD_ACADEMY_VERSION', self::version );
        define( 'WD_ACADEMY_FILE', __FILE__ );
        define( 'WD_ACADEMY_PATH', __DIR__ );
        define( 'WD_ACADEMY_URL', plugins_url( '', WD_ACADEMY_FILE ) );
        define( 'WD_ACADEMY_ASSETS', WD_ACADEMY_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        add_shortcode( 'wp_contact_form', [ $this, 'wp_contact_plugin' ] );
        add_shortcode( 'text_field', [ $this, 'wedev_text_input_form' ] );
        add_shortcode( 'select_field', [ $this, 'wedev_select_input_form' ] );
        add_shortcode( 'textarea_field', [ $this, 'wedev_textarea_input_form' ] );
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'wd_academy_installed' );

        if ( ! $installed ) {
            update_option( 'wd_academy_installed', time() );
        }
        update_option( 'wd_academy_version', WD_ACADEMY_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \CF_Shortcode
 */
function cf_shortcode() {
    return CF_Shortcode::init();
}

// kick-off the plugin
cf_shortcode();
