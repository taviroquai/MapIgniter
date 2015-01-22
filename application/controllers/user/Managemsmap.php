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

require_once APPPATH.'controllers/admin/adminmsmap.php';

class Managemsmap extends Adminmsmap {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'registered';
        $this->ctrlpath = 'user/'.$this->router->fetch_class();
        $this->mapctrlpath = 'user/managemap';
        $this->mslegendctrlpath = 'user/managemslegend';
        $this->mslayerctrlpath = 'user/managemslayer';
        $this->dataexplorerctrlpath = 'user/userdataexplorer';
    }
    
}