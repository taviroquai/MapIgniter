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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mapserver does not extent MY_Controller yet as it does not need
 * to load "rb" library which may decreasy performance
 */
class Mapserver extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load MapIgniter Configuration
        $this->load->config('mapigniter');
        // Load URL proxy
        $this->load->model('urlproxy_model');
    }
    
    public function index()
    {
        $url = $this->config->item('mapserver_cgi');
        $response = $this->urlproxy_model->request($url, array(), false);
        // Dump response content
        $this->output->set_output($response['content']);
    }
    
    public function map($alias)
    {
        
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
        $use_cache = $this->config->item('cache_on');
        $is_image = $this->input->get('FORMAT');
        $this->load->model('cache/filecache_model', 'filecache');
        
        // set default time to expire cached items
        $expired = $this->filecache->getExpireTime();
        
        // Only use cache for image requests
        if ($use_cache && $is_image && reset(explode('/', $is_image)) == 'image') {
            $format = end(explode('/', $is_image));
            // clean expired cached images
            $this->filecache->prob_clear();
            $this->filecache->outputItem($url, $format);
        }
        
        // Image not cached or cache outdated, we respond '200 OK' and output the image.
        // Make MapServer CGI request
        $response = $this->urlproxy_model->request($url, $post);
        // Cache headers for 5 minutes
        $this->output->set_header('Cache-Control: max-age='.$expired);
        $tsstring = gmdate('D, d M Y H:i:s', strtotime('+'.($expired/60).' minutes')).' GMT';
        $this->output->set_header("Last-Modified: $tsstring");
        $this->output->set_header("Expires: ".$tsstring);
        $this->output->set_header("MICache: original");
        $format = end(explode('/', $is_image));
        //$this->output->set_content_type('image/'.$format);
        
        // Save into cache; only for images
        if ($use_cache && $is_image && reset(explode('/', $is_image)) == 'image') {
            $this->filecache->saveItem($url, $response['content'], $format);
        }

        // Dump response content
        $this->output->set_output($response['content']);
    }
    
}
