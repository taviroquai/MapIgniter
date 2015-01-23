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

require_once APPPATH.'controllers/admin/Dataexplorer.php';

class Userdataexplorer extends Dataexplorer {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('admin/dataexplorer_model');
        $this->layout = 'module';
        $this->mainview = 'admin/dataexplorer';
        $this->ajaxmainview = 'admin/ajaxdataexplorer';
        $this->ctrlpath = 'user/'.$this->router->fetch_class();
    }
    
}