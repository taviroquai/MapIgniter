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

require_once APPPATH.'controllers/admin/adminmslegend.php';

class Managemslegend extends Adminmslegend {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
        
        $this->layout = 'registered';
        $this->ctrlpath = 'user/'.$this->router->fetch_class();
        $this->msmapctrlpath = 'user/managemsmap';
    }
    
}