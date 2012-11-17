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
        
        // Get post, if exists
        $post = file_get_contents("php://input");
        
        // Work with CodeIgniter cache
        // TODO: somebody needs to work on this!
        
        // CodeIgniter cache does not seems to work in this situation
        // http://codeigniter.com/user_guide/general/caching.html
        // $this->output->cache(1);
        // Moving to a custom cache system...
        
        // Set up cache
        $this->load->driver('cache');
        $cache_key = 'ms_'.sha1($url);
        $cached = $this->cache->apc->get($cache_key);

        // Checking if the client is validating his cache and if it is current.
        if (!empty($cached)) {
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= time())) {
                // Client's cache IS current, so we just respond '304 Not Modified'.
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()+300).' GMT', true, 304);
                header("MICache: skipped");
            }
            else {
                header('Cache-Control: max-age=300');
                $tsstring = gmdate('D, d M Y H:i:s', strtotime('+5 minutes')).' GMT';
                header("Last-Modified: $tsstring");
                header("Expires: ".$tsstring);
                header("MICache: cached");
                header('Content-Type: '.$cached['headers']['content_type']);
                echo $cached['content'];
            }
            exit();
        }

        
        // Image not cached or cache outdated, we respond '200 OK' and output the image.
        // Make MapServer CGI request
        $response = $this->urlproxy_model->request($url, $post);
        // Cache headers
        $this->output->set_header('Cache-Control: max-age=300');
        $tsstring = gmdate('D, d M Y H:i:s', strtotime('+5 minutes')).' GMT';
        $this->output->set_header("Last-Modified: $tsstring");
        $this->output->set_header("Expires: ".$tsstring);
        $this->output->set_header("MICache: original");
        $this->output->set_content_type($response['headers']['content_type']);

        // Save into cache for 5 minutes
        if (strstr($response['headers']['content_type'], 'image'))
                $this->cache->apc->save($cache_key, $response, 300);

        // Dump response content
        $this->output->set_output($response['content']);
    }
}
