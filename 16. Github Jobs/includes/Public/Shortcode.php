<?php
namespace Github\Public;

/**
 * Shortcode class
 */
class Shortcode{

    public $search ;

    /**
     * Initialize class
     */
    public function __construct() {
        add_action( 'wp_ajax_gj_search', [$this, 'ajax_search_handle'] );
        add_action( 'wp_ajax_nopriv_gj_search', [$this, 'ajax_search_handle'] );
        add_action( 'wp_ajax_single_search', [$this, 'single_search_handle'] );
        add_action( 'wp_ajax_nopriv_single_search', [$this, 'single_search_handle'] );
        add_shortcode( 'github-jobs', [$this, 'gihub_jobs_shortcode_cb'] );
    }

    /**
     * Handle Search Job Ajax Request
     *
     * @return void
     */
    public function ajax_search_handle() {
        $api_url = $_REQUEST['url'];
        $body = $this->set_jobs( $api_url );

        $data = json_decode( $body );
        $widthRow = '';

        if ( is_array( $data ) ) {
            foreach ($data as $key => $value) {            
                $widthRow .= "<tr>";
                $widthRow .= "<td>$value->title</td>";
                $widthRow .= "<td><button id='$value->id' class='pagerlink' > View Details </button></td>";
                $widthRow .= "</tr>";            
            }
        }
        wp_send_json_success( array( 'success' => true, 'data' =>  $widthRow ) );

        die();

    }

    /**
     * Single Post Job Description Ajax Request
     *
     * @return void
     */
    public function single_search_handle() {
        $api_url = $_REQUEST['url'];
        $body = $this->set_jobs( $api_url );

        $data = array (json_decode( $body ));
        
        if ( is_array( $data ) ) {
            $widthRow = '';
            foreach ($data as $key => $value) {
                $widthRow .= "<div><h3> $value->title </h3></div>";            
                $widthRow .= "<div> $value->description </div>";            
            }
        }
        
        wp_send_json_success( array( 'success' => true, 'data' =>  $widthRow ) );

        die();

    }

    /**
     * Render Shortcode [github-jobs]
     *
     * @return void
     */
    public function gihub_jobs_shortcode_cb() {
        wp_enqueue_script( 'job-search' );
        wp_enqueue_style( 'github-jobs' );

        $api_url = 'https://jobs.github.com/positions.json';
        
        //use transient api
        $key = 'gj_all_jobs';
        $body = get_transient( $key );

        if( empty( $body ) ) {
            $body = $this->set_jobs( $api_url );
            set_transient( $key, $body, DAY_IN_SECONDS );
        }

        $data = json_decode($body, true);
        
        ob_start();
        include __DIR__ . '/views/job-listing.php';
        $data = ob_get_clean();
        
        return $data;
    }

    /**
     * Retrive data using endpoinds of api
     *
     * @param string $url
     * @return Array Collection of $data
     */
    public function set_jobs( $url ) {
        $args = [
            'timeout' => 20
        ];
        
        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );

        return $body;
    }

}