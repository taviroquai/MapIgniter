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

class Admincontrollers extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        // Set layout
        $this->layout = 'admin';
    }
    
    /**
     * Action index
     * Display a list of Layouts
     * TODO: Pagination
     */
    public function index()
    {   
        // Load All controllers
        // TODO: Pagination
        $beans = $this->database_model->find('controller', ' true ');
        
        // Load main content
        $content = $this->load->view('admin/controllers/admincontrollers', array('items' => $beans), TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for controller edition
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            $bean = $this->database_model->load('controller', $id);
            if (!$bean) throw new Exception('Controller not found!');
            
            // Load all layouts
            $layouts = $this->database_model->find('layout', ' true ');
            
            // Load main content
            $data = array(
                'controller'    => $bean,
                'layouts'       => $layouts);
            $content = $this->load->view('admin/controllers/admineditcontroller', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of the controller
     * @param string $id 
     */
    public function save($id)
    {   
        try {
            // load post data
            $path = $this->input->post('path');
            $layout_id = $this->input->post('layout_id');
            
            // Create new controller
            if ($id === 'new') {
                $bean = $this->database_model->create('controller');
            }
            // Load existing controller
            else {
                $bean = $this->database_model->load('controller', $id);
                if (!$bean) throw new Exception('Controller not found!');
            }

            // Set new data and save
            if (!empty($path) && !empty($layout_id)) {
                $bean->path = $path;
                $bean->layout = $this->database_model->load('layout', $layout_id);
                $this->database_model->save($bean);
            }
        }
        catch(Exception $e) {
            //
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/admincontrollers/edit/'.$bean->id);
    }
    
    /**
     * Action delete
     * Deleted the selected controllers
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->database_model->delete($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/admincontrollers');
    }
    
}