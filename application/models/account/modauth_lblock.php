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

class Modauth_lblock extends Lblock_model {
    
    protected $post;
    
    public function __construct() {
        parent::__construct();
        
        // Load language
        $this->lang->load('auth', $this->session->userdata('lang'));
        
        // Set view
        $this->view = 'auth/menu';
        
        // Set links
        $this->links = array(base_url().'web/auth/auth.css');
        
        // Set scripts
        $this->scripts = array();
        
        // Load account model
        $this->load->model('account/account_model');
        
        // Get current session username
        $username = $this->session->userdata('username');
        
        // Get current session username
        if ($username == 'guest') {
            $this->addData('form', array('username' => '', 'password' => ''));
        }
        else {
            // Load user account
            $account = $this->account_model->load($username);
            $this->addData('account', $account->export());
        }
    }
    
}
?>
