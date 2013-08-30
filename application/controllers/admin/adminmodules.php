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

class Adminmodules extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('layout/layout_model');
    }
    
    /**
     * Action index
     * Display a list of registered Modules
     * TODO: Pagination
     */
    public function index()
    {   
        // Load All Blocks
        // TODO: Pagination
        $items = $this->layout_model->loadModuleAll();
        
        // Load main content
        $content = $this->load->view('admin/layouts/adminmodules', array('items' => $items), TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for block edition
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load block
            $bean = $this->layout_model->loadModule($id);
            if (!$bean) throw new Exception('Module not found!');
            
            // Load main content
            $data = array('mod' => $bean);
            $content = $this->load->view('admin/layouts/admineditmodule', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of the module
     * @param string $id 
     */
    public function save($id)
    {   
        try {
            // load post data
            $path = $this->input->post('path');
            $name = $this->input->post('name');
            $table = $this->input->post('table');
            
            // Create new block
            if ($id === 'new') {
                $mod = $this->layout_model->createModule($name, $path, $table);
                $account = $this->account_model->load($this->session->userdata('username'));
                $mod->owner = $account;
            }
            // Load existing block
            else {
                $mod = $this->layout_model->loadModule($id);
                if (!$mod) throw new Exception('Module not found!');
            }

            // Set new data and save
            if (!empty($path) && !empty($name)) {
                if (!file_exists(APPPATH.'/models/'.$path.'.php'))
                        throw new Exception('File not found!');
                $mod->name = $name;
                $mod->path = $path;
                $mod->table = $table;
                $this->layout_model->save($mod);    
            }
            
            if (!$this->input->is_ajax_request())
                redirect(base_url().'admin/adminmodules/edit/'.$mod->id);
        }
        catch(Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        
    }
    
    /**
     * Action delete
     * Deleted the selected modules
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->layout_model->deleteModule($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminmodules');
    }
    
    /**
     * Loads the layout and renders out
     * @param string $content
     * @return null
     */
    protected function render($content) {
        if ($this->input->is_ajax_request()) {
            echo $content;
            return;
        }
        // Load layout and render
        $this->loadLayout('admin', $content);
    }
    
}