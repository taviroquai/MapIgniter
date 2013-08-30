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

class Auth extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load language
        $this->lang->load('auth', $this->session->userdata('lang'));
        
        // Load account model
        $this->load->model('account/account_model');
        $this->load->model('account/gaccount_model');
        
        // Load language
        $this->lang->load('auth', $this->session->userdata('lang'));
    }
    
    public function index()
    {   
        $post = $this->input->post(NULL, TRUE);
        $data = array('form' => array('username' => '', 'password' => ''));
        if (!empty($post)) {
            $data['form']['username'] = $post['username'];
            $username = $this->session->userdata('username');
            if ($username == 'guest') {
                $password = $this->account_model->secure($post['password']);
                $account = $this->account_model->load($post['username']);
                if (empty($account) || $account->password != $password) {
                    $data['form']['msg'] = 'Wrong username or password!';
                }
                else {
                    $username = $account->username;
                    $this->session->set_userdata('username', $account->username);
                    redirect(base_url().'user/user');
                }
            }
            else $result = false;
        }

        // output
        $data['gauth_url'] = $this->gaccount_model->login_url();
        $content = $this->load->view('auth/auth', $data, TRUE);
        $ldata['pagetitle'] = $this->lang->line('title');
        $this->render($content);
    }
    
    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url());
    }
}