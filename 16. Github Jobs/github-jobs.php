<?php
/**
 * Plugin Name: Github Jobs
 * Description: A plugin that display github recent jobs and search their desire jobs.
 * Plugin URI: https://github.com/abdulhadicse
 * Author: Abdul Hadi
 * Author URI: https://www.abdulhadi.info
 * Version: 1.0.0
 * License: GPL2 or later
 * Text Domain: search-jobs
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
/**
 * The main plugin class
 */
final class Github_Jobs {

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
     * @return \Github_Jobs
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
        define( 'GITHUB_JOBS_VERSION', self::version );
        define( 'GITHUB_JOBS_FILE', __FILE__ );
        define( 'GITHUB_JOBS_PATH', __DIR__ );
        define( 'GITHUB_JOBS_URL', plugins_url( '', GITHUB_JOBS_FILE ) );
        define( 'GITHUB_JOBS_ASSETS', GITHUB_JOBS_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        new Github\Assets();
        new Github\Frontend();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'github_jobs_installed' );

        if ( ! $installed ) {
            update_option( 'github_jobs_installed', time() );
        }

        update_option( 'github_jobs_version', GITHUB_JOBS_VERSION );
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Github_Jobs
 */
function github_jobs() {
    return Github_Jobs::init();
}

// kick-off the plugin
github_jobs();