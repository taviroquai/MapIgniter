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
 * @link		http://marcoafonso.com/miwiki/doku.php
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mapserver does not extent MY_Controller yet as it does not need
 * to load "rb" library which may decreasy performance
 */
class Mapserver extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
    }
    
    public function index()
    {

    }
    
    public function map($alias)
    {
        
        // Load Url Proxy
        $this->load->model('urlproxy_model');
        
        // Translate URL
        $url = $this->config->item('mapserver_cgi');
        if (!empty($alias)) {
            $newmap = $this->config->item('private_data_path').'mapfile/'.$alias.'.map&';
            $url = $url . 'map='.$newmap.$_SERVER['QUERY_STRING'];
        }

        // Teste Postgis + sld
        //if (strstr($url, 'postgis')) $url .= '&'.'SLD=http://localhost/websig1/index.php/testes/sld';
        
        // Make Mapserver CGI request
        $post = file_get_contents("php://input");
        $response = $this->urlproxy_model->request($url, $post);

        // Set response headers
        header("Content-Type: ".$response['headers']['content_type']);

        // Dump response content
        echo $response['content'];

    }
    
}