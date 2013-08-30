<?php

/**
 * MapIgniter
 *
 * An open source GeoCMS application
 *
 * @package		MapIgniter
 * @author		Marco Afonso
 * @copyright	Copyright (c) 2012, Marco Afonso
 * @license		dual license, one of two: Apache v2 or GPL
 * @link		http://mapigniter.com/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

class Proxy extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        
        // Load Proxy Model
        $this->load->model('urlproxy_model');
        
        // Get URL
        $url = $_GET['url'];
        
        // Make request
        $response = $this->urlproxy_model->request($url);

        // Set response headers
        header("Content-Type: ".$response['headers']['content_type']);

        // Dump response content
        echo $response['content'];

    }
    
}

?>
