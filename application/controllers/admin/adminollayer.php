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

class Adminollayer extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('openlayers/openlayers_model');
        $this->load->model('layer_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
    }
    
    /**
     * Action index
     * Display a list of openlayers layers
     */
    public function index()
    {   
        // Load all layers
        // TODO: Pagination
        $items = $this->openlayers_model->loadLayerAll();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        $content = $this->load->view('admin/openlayers/adminlayer', $data, TRUE);
        
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
            
            // Create new openlayers layer
            if ($id === 'new') {
                $layer = $this->layer_model->load($layer_id);
                if (empty($layer)) throw new Exception ('Layer not found!');
                $layertype = $this->openlayers_model->loadLayerType(1);
                $bean = $this->openlayers_model->createLayer($layer, $layertype);
                $bean->options = "{\n\"isBaseLayer\":true\n}";
                $bean->vendorparams = "{\n\"layers\":\"".$layer->alias."\",\n\"transparent\":true\n}";
            }
            // Load layer
            else {
                $bean = $this->openlayers_model->loadLayer($id);
                if (!$bean) throw new Exception('OpenLayers layer not found!');
            }
            
            // Load layer types
            $ollayertypes = $this->openlayers_model->loadLayerTypeAll();
            
            // Load Maps
            $this->load->model('map_model');
            $maps = $this->map_model->loadAll();
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'ollayer' => $bean,
                'ollayertypes' => $ollayertypes,
                'maps' => $maps,
                'action' => '/save/'.(empty($bean->id) ? 'new' : $bean->id));
            $content = $this->load->view('admin/openlayers/admineditlayer', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of openlayers layer
     * @param string $id 
     */
    public function save($id)
    {   
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new OpenLayers layer
            if ($id === 'new') {
                $layer = $this->layer_model->load($post['layer_id']);
                if (empty($layer)) throw new Exception('Layer not found');
                $ollayertype = $this->openlayers_model->loadLayerType($post['ollayertype_id']);
                if (empty($ollayertype)) throw new Exception('Layer type not found!');
                $ollayer = $this->openlayers_model->createLayer($layer, $ollayertype);
                $ollayer->owner = $this->account;
            }
            // Load existing layer
            else {
                $ollayer = $this->openlayers_model->loadLayer($id);
                if (!$ollayer) throw new Exception('OpenLayers layer not found!');
            }

            // TODO: Validate data
            $fields = array(
                'url',
                'options',
                'vendorparams',
                'informationurl',
                'default_style'
            );
            
            $ollayer->import($post, implode(',', $fields));
            $ollayer->layer = $this->layer_model->load($post['layer_id']);
            $ollayer->ollayertype = $this->openlayers_model->loadLayerType($post['ollayertype_id']);
            $this->openlayers_model->save($ollayer);
            $info[] = 'The layers was saved';
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load layer types
        $ollayertypes = $this->openlayers_model->loadLayerTypeAll();
        
        // Load Maps
        $this->load->model('map_model');
        $maps = $this->map_model->loadAll();

        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'ollayer' => $ollayer,
            'ollayertypes' => $ollayertypes,
            'maps' => $maps,
            'action' => '/save/'.$ollayer->id);
        $content = $this->load->view('admin/openlayers/admineditlayer', $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action delete
     * Deleted the selected OpenLayers layer
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->openlayers_model->deleteLayer($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath);
    }
    
}