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

class Admingelayertype extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'admin';
        $this->load->model('googleearth/googleearth_model');
    }
    
    /**
     * Action index
     * Display a list of Google Earth layer types
     */
    public function index()
    {   
        // Load all layer types
        // TODO: Pagination
        $items = $this->googleearth_model->loadLayerTypeAll();
        
        // Temporary layer type
        $bean = $this->googleearth_model->createLayerType();
        
        // Load main content
        $data = array(
            'items' => $items,
            'gelayertype' => $bean,
            'action' => 'admin/admingelayertype/save/new');
        $content = $this->load->view('admin/googleearth/adminlayertype', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of Google earth layer type
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load layer type
            $bean = $this->googleearth_model->loadLayerType($id);
            if (!$bean) throw new Exception('Layer type not found!');
            
            // Load main content
            $data = array(
                'gelayertype' => $bean,
                'action' => 'admin/admingelayertype/save/'.$bean->id);
            $content = $this->load->view('admin/googleearth/admineditlayertype', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of Google earth layer type
     * @param string $id 
     */
    public function save($id)
    {   
        try {
            // load post data
            $type = $this->input->post('type', TRUE);
            
            // Create a new Google earth layer type
            if ($id === 'new') {
                $gelayertype = $this->googleearth_model->createLayerType();
                $account = $this->account_model->load($this->session->userdata('username'));
                $gelayertype->owner = $account;
            }
            // Load existing Google Earth layer type
            else {
                $gelayertype = $this->googleearth_model->loadLayerType($id);
                if (!$gelayertype) throw new Exception('Layer type not found!');
            }

            // Validate data
            if (empty($type)) throw new Exception ('Invalid name');
            if ($id === 'new') {
                $exists = $this->database_model->findOne('gelayertype', ' type = ?', array($type));
                if (!empty($exists)) throw new Exception('Duplicated Google Earth layer type!');
            }
            
            // Save data
            $gelayertype->type = $type;
            $this->googleearth_model->save($gelayertype);    

            if (!$this->input->is_ajax_request())
                redirect(base_url().'admin/admingelayertype/edit/'.$gelayertype->id);
            
        }
        catch(Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        
    }
    
    /**
     * Action delete
     * Deleted the selected Google Earth layer type
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->googleearth_model->deleteLayerType($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/admingelayertype');
    }
    
}