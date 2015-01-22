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

class Adminlayer extends MY_Controller {

    protected $pglayerctrlpath;
    protected $mslayerctrlpath;
    protected $ollayerctrlpath;
    protected $gelayerctrlpath;
    protected $listview;
    protected $editview;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        $this->load->model('layer_model');
        $this->load->model('rating/rating_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->pglayerctrlpath = 'admin/adminpglayer';
        $this->mslayerctrlpath = 'admin/adminmslayer';
        $this->ollayerctrlpath = 'admin/adminollayer';
        $this->gelayerctrlpath = 'admin/admingelayer';
        $this->listview = 'admin/layer/adminlayer';
        $this->editview = 'admin/layer/admineditlayer';
    }
    
    /**
     * Action index
     * Loads layers from model
     * Display a list of layers
     */
    public function index()
    {
        try {
            // Load all layers
            // TODO: Pagination
            $items = $this->layer_model->loadAll();

            // Temp layer
            $layer = $this->layer_model->create();

            // Load main content
            $data = array(
                'items' => $items,
                'parentLayers' => $items,
                'layer' => $layer,
                'ctrlpath' => $this->ctrlpath,
                'action' => '/save/new');
            $content = $this->load->view($this->listview, $data, TRUE);

            // Render
            $this->render($content);
        }
        catch (Exception $e) { show_error($e->getMessage()); }
    }
    
    /**
     * Action edit
     * Loads layer from model
     * Opens a form for edition of the layer
     * @param string $id Layer ID
     */
    public function edit($id)
    {   
        try {
            // Load layer
            $bean = $this->layer_model->load($id);
            if (!$bean) throw new Exception('The requested layer does not exists!');
            
            // Load main content
            $data = array(
                'layer' => $bean,
                'parentLayers' => $this->layer_model->loadAll(),
                'ctrlpath' => $this->ctrlpath,
                'pglayerctrlpath' => $this->pglayerctrlpath,
                'mslayerctrlpath' => $this->mslayerctrlpath,
                'ollayerctrlpath' => $this->ollayerctrlpath,
                'gelayerctrlpath' => $this->gelayerctrlpath,
                'action' => '/save/'.$bean->id);
            $content = $this->load->view($this->editview, $data, TRUE);
            
            // Render
            $this->render($content);
        }
        catch (Exception $e) { show_error($e->getMessage()); }
        
    }
    
    /**
     * Action save
     * Loads layer from model
     * Validates data
     * Saves the new data of the layer
     * @param string $id 
     */
    public function save($id)
    {   
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);

            // Create new layer
            if ($id === 'new') {
                $layer = $this->layer_model->create();
                $account = $this->account_model->load($this->session->userdata('username'));
                $layer->owner = $account;
            }
            // Load existing layer
            else {
                $layer = $this->layer_model->load($id);
                if (!$layer) throw new Exception('Layer not found!');
            }

            // Validate data and save
            if (empty($post['title'])) throw new Exception('Invalid title');
            if (empty($post['alias']) || strlen($post['alias']) < 5)
                throw new Exception('System name has to have at least 5 characters');
            
            // Save
            $fields = array('title', 'description', 'alias');
            $layer->import($post, implode(',', $fields));
            if (!empty($post['owner'])) {
                $owner = $this->database_model->findOne('account', 'username = ?', array($post['owner']));
                if (!empty($owner)) $layer->owner = $owner;
            }
            if (!empty($post['parent'])) {
                $layer->parent = $this->layer_model->load($post['parent']);
            }
            $this->layer_model->save($layer);
            $info[] = 'The layer was saved';
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'layer' => $layer,
            'parentLayers' => $this->layer_model->loadAll(),
            'ctrlpath' => $this->ctrlpath,
            'pglayerctrlpath' => $this->pglayerctrlpath,
            'mslayerctrlpath' => $this->mslayerctrlpath,
            'ollayerctrlpath' => $this->ollayerctrlpath,
            'gelayerctrlpath' => $this->gelayerctrlpath,
            'action' => '/save/'.$layer->id);
        $content = $this->load->view($this->editview, $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action delete
     * Deleted the selected layer
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->layer_model->delete($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath);
    }
    
    /**
     * Action delete mapserver layer
     * Remove associated layer
     */
    public function delmslayer($mslayer_id)
    {
        $this->load->model('mapserver/mapserver_model');
        $mslayer = $this->mapserver_model->loadLayer($mslayer_id);
        $layer_id = $mslayer->layer->id;
        $this->mapserver_model->deleteLayer(array($mslayer_id));
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$layer_id);
    }
    
    /**
     * Action delete postgis layer
     * Remove associated layer
     */
    public function delpglayer($pglayer_id)
    {
        $this->load->model('database/postgis_model');
        $pglayer = $this->postgis_model->loadLayer($pglayer_id);
        $layer_id = $pglayer->layer->id;
        $this->postgis_model->deleteLayer(array($pglayer_id));
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$layer_id);
    }
    
    /**
     * Action delete openlayers layer
     * Remove associated layer
     */
    public function delollayer($ollayer_id)
    {
        $this->load->model('openlayers/openlayers_model');
        $ollayer = $this->openlayers_model->loadLayer($ollayer_id);
        $layer_id = $ollayer->layer->id;
        $this->openlayers_model->deleteLayer(array($ollayer_id));
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$layer_id);
    }
    
    /**
     * Action delete Google earth layer
     * Remove associated layer
     */
    public function delgelayer($gelayer_id)
    {
        $this->load->model('googleearth/googleearth_model');
        $gelayer = $this->googleearth_model->loadLayer($gelayer_id);
        $layer_id = $gelayer->layer->id;
        $this->googleearth_model->deleteLayer(array($gelayer_id));
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$layer_id);
    }
    
}