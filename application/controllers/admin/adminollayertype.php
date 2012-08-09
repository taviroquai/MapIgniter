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

class Adminollayertype extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('openlayers/openlayers_model');
    }
    
    /**
     * Action index
     * Display a list of OpenLayers layer types
     */
    public function index()
    {   
        // Load all layer types
        // TODO: Pagination
        $items = $this->openlayers_model->loadLayerTypeAll();
        
        // Temporary layer type
        $bean = $this->openlayers_model->createLayerType();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ollayertype' => $bean,
            'action' => 'admin/adminollayertype/save/new');
        $content = $this->load->view('admin/openlayers/adminlayertype', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of OpenLayers layer type
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load layer type
            $bean = $this->openlayers_model->loadLayerType($id);
            if (!$bean) throw new Exception('Layer type not found!');
            
            // Load main content
            $data = array(
                'ollayertype' => $bean,
                'action' => 'admin/adminollayertype/save/'.$bean->id);
            $content = $this->load->view('admin/openlayers/admineditlayertype', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of OpenLayers layer type
     * @param string $id 
     */
    public function save($id)
    {   
        try {
            // load post data
            $type = $this->input->post('type', TRUE);
            $classname = $this->input->post('classname', TRUE);
            
            // Create a new OpenLayers layer type
            if ($id === 'new') {
                $ollayertype = $this->openlayers_model->createLayerType();
                $account = $this->account_model->load($this->session->userdata('username'));
                $ollayertype->owner = $account;
            }
            // Load existing OpenLayers layer type
            else {
                $ollayertype = $this->openlayers_model->loadLayerType($id);
                if (!$ollayertype) throw new Exception('Layer type not found!');
            }

            // Validate data
            if (empty($type)) throw new Exception ('Invalid name');
            if (empty($classname)) throw new Exception ('Invalid classname');
            if ($id === 'new') {
                $exists = $this->database_model->findOne('ollayertype', ' classname = ?', array($classname));
                if (!empty($exists)) throw new Exception('Duplicated OpenLayers classname!');
            }
            
            // Save data
            $ollayertype->type = $type;
            $ollayertype->classname = $classname;
            $this->openlayers_model->save($ollayertype);    

            if (!$this->input->is_ajax_request())
                redirect(base_url().'admin/adminollayertype/edit/'.$ollayertype->id);
            
        }
        catch(Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        
    }
    
    /**
     * Action delete
     * Deleted the selected OpenLayers layer type
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->openlayers_model->deleteLayerType($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminollayertype');
    }
    
}