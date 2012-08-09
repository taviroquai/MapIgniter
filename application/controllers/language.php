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

class Language extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'module';
    }
    
    public function index()
    {   
        
    }
    
    public function sessionset($lang) {
        $this->session->set_userdata('lang', $lang);
        redirect(base_url());
    }
}