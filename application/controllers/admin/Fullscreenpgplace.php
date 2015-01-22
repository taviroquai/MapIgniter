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

require_once APPPATH.'controllers/admin/adminpgplace.php';

class Fullscreenpgplace extends Adminpgplace {

    protected $listpgplaceview;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/postgis_model');
        
        $this->layout = 'fullscreenedit';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->listpgplaceview = 'admin/place/adminpgplacerecordsfscreen';
        $this->pgplacectrl = 'admin/fullscreenpgplace';
    }
    
    /**
     * Action index    
     * Display a list of all table records
     */
    public function listitemstable($id)
    {
        $this->listpgplaceview = 'admin/place/adminpgplacetablefscreen';
        parent::listitems($id);
    }
    
}