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

class Admincache extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'admin';
    }
    
    public function index()
    {
        // Get cache information
        $info = array('total_files' => 0, 'bytes' => 0);
        $this->load->model('cache/filecache_model', 'filecache');
        $info['total_files'] = $this->filecache->getTotalItems();
        $info['total_size'] = $this->filecache->formatSize($this->filecache->getSize());
        $data['info'] = $info;
        
        // Load main content
        $content = $this->load->view('admin/cache/filecache', $data, TRUE);
        
        // Render content
        $this->render($content);

    }
    
    public function clear() {
        // Clear cache
        $this->load->model('cache/filecache_model', 'filecache');
        $this->filecache->clear();
        redirect(base_url().'admin/admincache');
    }
    
    

}