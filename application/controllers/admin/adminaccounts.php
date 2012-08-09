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

/**
 * @package     Controllers
 * @category    User Account Administration
 * @author      mafonso
 */
class Adminaccounts extends MY_Controller {

    /**
     * Contructor
     */
    public function __construct() {
        parent::__construct();
        
        $this->load->model('account/account_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        
        $this->lang->load('account', 'pt-PT');
    }
    
    /**
     * Action index
     * Loads user accounts from model
     * Display user accounts list
     * @todo pagination
     */
    public function index()
    {   
        try {
            // Load content to render
            $data = array(
                'account' => $this->account_model->create(),
                'items' => $this->account_model->loadAll(),
                'ctrlpath' => $this->ctrlpath);
            $content = $this->load->view('admin/accounts/adminaccounts', $data, TRUE);
            $this->render($content);
        }
        catch (Exception $e) { show_error($e->getMessage()); }
    }
    
    /**
     * Action edit
     * Loads user account from model
     * Display user account form
     * @param string $id User account ID
     */
    public function edit($id)
    {   
        try {
            $account = $this->account_model->loadById($id);
            if (!$account) throw new Exception(sprintf($this->lang->line('error.account.load'), $id, base_url().$this->ctrlpath));
            
            // Load content to render
            $data = array(
                'account' => $account,
                'groups' => $this->group_model->loadAll(),
                'ctrlpath' => $this->ctrlpath);
            $content = $this->load->view('admin/accounts/admineditaccount', $data, TRUE);
            $this->render($content);
        }
        catch (Exception $e) { show_error($e->getMessage()); }
        
    }
    
    /**
     * Action save
     * Loads user account from model
     * Validate user account POST data
     * Ask model to save user account
     * @param string $id User account ID
     * @todo validation
     */
    public function save($id)
    {
        try {
            $errors = array(); $info = array();
            $post = $this->input->post(NULL, TRUE);
            $account = $this->account_model->saveAccountForm($post, $id, $errors, $info);
            
            // Load content to render
            $data = array(
                'msgs' => array('errors' => $errors, 'info' => $info),
                'account' => $account,
                'groups' => $this->group_model->loadAll(),
                'ctrlpath' => $this->ctrlpath);
            $content = $this->load->view('admin/accounts/admineditaccount', $data, TRUE);
            $this->render($content);
        }
        catch (Exception $e) { show_error($e->getMessage()); }

    }
    
    /**
     * Action savegroups
     * Loads user account from model
     * Ask model to save user account groups
     * @param type $id User account ID
     */
    public function savegroups($id) {
        try {
            $errors = array(); $info = array();
            $post = $this->input->post(NULL, TRUE);
            $account = $this->account_model->saveGroupsForm($post, $id, $errors, $info);
            
            // Load content to render
            $data = array(
                'msgs' => array('errors' => $errors, 'info' => $info),
                'account' => $account,
                'groups' => $this->group_model->loadAll(),
                'ctrlpath' => $this->ctrlpath);
            $content = $this->load->view('admin/accounts/admineditaccount', $data, TRUE);
            $this->render($content);
        }
        catch (Exception $e) { show_error($e->getMessage()); }
        
    }
    
    /**
     * Action delete
     * Ask model to delete user accounts
     */
    public function delete()
    {
        try {
            $errors = array(); $info = array();
            $post = $this->input->post(NULL, TRUE);
            $this->account_model->deleteAccountForm($post, $errors, $info);
            
            // Load content to render
            $data = array(
                'msgs' => array('errors' => $errors, 'info' => $info),
                'account' => $this->account_model->create(),
                'items' => $this->account_model->loadAll(),
                'ctrlpath' => $this->ctrlpath);
            $content = $this->load->view('admin/accounts/adminaccounts', $data, TRUE);
            $this->render($content);
        }
        catch (Exception $e) { show_error($e->getMessage()); }
        
    }
    
}