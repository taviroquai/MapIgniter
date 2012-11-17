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

class Admincache extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'admin';
    }
    
    public function index()
    {
        // Get cache information
        $data['info'] = apc_cache_info('user');
        
        // Load main content
        $content = $this->load->view('admin/cache/info', $data, TRUE);
        
        // Render content
        $this->render($content);

    }
    
    public function clear()
    {
        // Clear APC cache
        apc_clear_cache('user');
        redirect(base_url().'admin/admincache');
    }

}