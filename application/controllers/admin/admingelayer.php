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

class Admingelayer extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('googleearth/googleearth_model');
        $this->load->model('layer_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
    }
    
    /**
     * Action index
     * Display a list of googleearth layers
     */
    public function index()
    {   
        // Load all layers
        // TODO: Pagination
        $items = $this->googleearth_model->loadLayerAll();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        $content = $this->load->view('admin/googleearth/adminlayer', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a layer
     * @param string $id 
     */
    public function edit($id, $layer_id = null)
    {   
        try {
            
            // Create new googleearth layer
            if ($id === 'new') {
                $layer = $this->layer_model->load($layer_id);
                if (empty($layer)) throw new Exception ('Layer not found!');
                $layertype = $this->googleearth_model->loadLayerType(1);
                $bean = $this->googleearth_model->createLayer($layer, $layertype);
            }
            // Load layer
            else {
                $bean = $this->googleearth_model->loadLayer($id);
                if (!$bean) throw new Exception('Google Earth layer not found!');
            }
            
            // Load layer types
            $gelayertypes = $this->googleearth_model->loadLayerTypeAll();
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'gelayer' => $bean,
                'gelayertypes' => $gelayertypes,
                'action' => '/save/'.(empty($bean->id) ? 'new' : $bean->id));
            $content = $this->load->view('admin/googleearth/admineditlayer', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of googleearth layer
     * @param string $id 
     */
    public function save($id)
    {   
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new Google Earth layer
            if ($id === 'new') {
                $layer = $this->layer_model->load($post['layer_id']);
                if (empty($layer)) throw new Exception('Layer not found');
                $gelayertype = $this->googleearth_model->loadLayerType($post['gelayertype_id']);
                if (empty($gelayertype)) throw new Exception('Layer type not found!');
                $gelayer = $this->googleearth_model->createLayer($layer, $gelayertype);
                $gelayer->owner = $this->account;
            }
            // Load existing layer
            else {
                $gelayer = $this->googleearth_model->loadLayer($id);
                if (!$gelayer) throw new Exception('Google Earth layer not found!');
            }
            
            $gelayer->import($post);
            $gelayer->layer = $this->layer_model->load($post['layer_id']);
            $gelayer->gelayertype = $this->googleearth_model->loadLayerType($post['gelayertype_id']);
            $this->googleearth_model->save($gelayer);
            $info[] = 'The layer was saved';
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load layer types
        $gelayertypes = $this->googleearth_model->loadLayerTypeAll();

        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'gelayer' => $gelayer,
            'gelayertypes' => $gelayertypes,
            'action' => '/save/'.$gelayer->id);
        $content = $this->load->view('admin/googleearth/admineditlayer', $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action delete
     * Deleted the selected Google Earth layer
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->googleearth_model->deleteLayer($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath);
    }
    
}