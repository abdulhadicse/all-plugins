<?php

namespace WPCFORM;
/**
 * Frontend class
 */
class Frontend {
    /**
     * Initialize class
     */
    public function __construct() {
        new Public\Shortcode();
    }
}