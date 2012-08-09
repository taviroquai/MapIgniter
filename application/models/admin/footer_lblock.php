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

require_once APPPATH.'models/layout/lblock_model.php';

class Footer_lblock extends Lblock_model {
    
    public function __construct() {
        parent::__construct();
        
        // Set view
        $this->view = 'admin/footer';
        
        $this->links = array(
            base_url().'web/admin/footer.css'
        );
        
    }
    
}
?>
