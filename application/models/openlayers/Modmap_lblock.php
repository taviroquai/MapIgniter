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

require_once APPPATH.'models/layout/Lblock_model.php';

class Modmap_lblock extends Lblock_model {
    
    public function __construct() {
        parent::__construct();
        
        // Load language
        $this->lang->load('map', $this->session->userdata('lang'));
        
        $this->view = 'openlayers/mapblock';
        
        $this->links = array(
            base_url()."web/js/vendor/ol/theme/default/style.css",
            base_url()."web/openlayers/mapblock.css"
        );
        
        $this->scripts = array(
            base_url()."web/js/vendor/ol/OpenLayers.js", // OpenLayers 2.12
            base_url()."web/js/WebSig.js"
        );
        
    }
    
}

?>
