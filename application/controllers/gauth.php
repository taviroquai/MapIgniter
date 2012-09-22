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

class Gauth extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        // Set default view
        $this->layout = 'module';
        
        // Load account model
        $this->load->model('account/gaccount_model');
        
    }
    
    public function index()
    {
        $username = $this->session->userdata('username');
        if ($username == 'guest') {
            $data['login_url'] = $this->gaccount_model->login_url();
        }
        else {
            $data['account'] = $this->gaccount_model->load($username);
        }
        $content = $this->load->view('auth/gauth', $data, TRUE);
        $this->render($content);
    }
    
    public function logged() {
        $errors = array();
        $username = $this->session->userdata('username');
        if ($username == 'guest') {
            $account = $this->gaccount_model->logged($errors);
            if (empty($account)) {
                $content = implode(', ', $errors);
                $this->render($content);
                return;
            }
            else {
                $this->session->set_userdata('username', $account->username);
            }
        }
        redirect(base_url().'user/user');
    }
}