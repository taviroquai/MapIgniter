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

class Adminolmap extends MY_Controller {

    protected $mapctrlpath;
    protected $ollayerctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('openlayers/openlayers_model');
        $this->load->model('map_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->mapctrlpath = 'admin/adminmap';
        $this->ollayerctrlpath = 'admin/adminollayer';
    }
    
    /**
     * Action index
     * Display a list of OpenLayers maps
     */
    public function index()
    {   
        // Load all map
        // TODO: Pagination
        $items = $this->openlayers_model->loadMapAll();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        $content = $this->load->view('admin/openlayers/adminmap', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a map
     * @param string $id 
     */
    public function edit($id, $map_id = null)
    {   
        try {
            
            // Create new map
            if ($id === 'new') {
                $map = $this->map_model->load($map_id);
                if (empty($map)) throw new Exception ('Map not found!');
                $bean = $this->openlayers_model->createMap($map);
            }
            // Load map
            else {
                $bean = $this->openlayers_model->loadMap($id);
                if (!$bean) throw new Exception('OpenLayers map not found!');
            }
            
            // Load main content
            $data = array(
                'olmap' => $bean,
                'ctrlpath' => $this->ctrlpath,
                'ollayerctrlpath' => $this->ollayerctrlpath,
                'ollayers' => $this->openlayers_model->loadLayerAll());
            if ($id === 'new') $data['action'] = '/save/new';
            else $data['action'] = '/save/'.$bean->id;
            $content = $this->load->view('admin/openlayers/admineditmap', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of map
     * @param string $id 
     */
    public function save($id)
    {   
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new mapfile
            if ($id === 'new') {
                $map = $this->map_model->load($post['map_id']);
                if (empty($map)) throw new Exception('Map not found');
                $olmap = $this->openlayers_model->createMap($map);
                $account = $this->account_model->load($this->session->userdata('username'));
                $olmap->owner = $account;
            }
            // Load existing style
            else {
                $olmap = $this->openlayers_model->loadMap($id);
                if (!$olmap) throw new Exception('OpenLayers map not found!');
            }

            // TODO: Validate data

            $fields = array(
                'projection',
                'maxextent',
                'restrictedextent',
                'autoresolution',
                'maxresolution',
                'numzoomlevels',
                'units'
            );
            
            // Set new data and save
            $olmap->import($post, implode(',', $fields));
            $this->openlayers_model->save($olmap);
            $info[] = 'The OpenLayers map was saved';
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'olmap' => $olmap,
            'ctrlpath' => $this->ctrlpath,
            'ollayerctrlpath' => $this->ollayerctrlpath,
            'action' => '/save/'.$olmap->id,
            'ollayers' => $this->openlayers_model->loadLayerAll());
        $content = $this->load->view('admin/openlayers/admineditmap', $data, TRUE);
        $this->render($content);
        
    }
    
    /**
     * Action delete
     * Deleted the selected map
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->openlayers_model->deleteMap($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->mapctrlpath);
    }
    
    /**
     * Action addlayer
     * Adds selected layers to the map
     */
    public function addlayer($olmap_id)
    {
        $olmap = $this->openlayers_model->loadMap($olmap_id);
        if (empty($olmap)) return;
        $selected = $this->input->post('selected');
        foreach ($selected as $ollayer_id) {
            $ollayer = $this->openlayers_model->loadLayer($ollayer_id);
            if (empty($ollayer)) continue;
            $this->openlayers_model->addMapLayer($olmap, $ollayer);
        }
        
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$olmap_id);
    }
    
    /**
     * Action dellayer
     * Removes selected layers from map
     */
    public function dellayer($olmap_id)
    {
        $olmap = $this->openlayers_model->loadMap($olmap_id);
        if (empty($olmap)) return;
        $selected = $this->input->post('selected');
        $this->openlayers_model->delMapLayer($olmap, $selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$olmap_id);
    }
    
    /**
     * Action set layer display order
     * Removes selected layers from map
     */
    public function setLayerDisplayOrder($olmap_id, $oldOrder, $newOrder)
    {
        $olmap = $this->openlayers_model->loadMap($olmap_id);
        $this->openlayers_model->changeLayersDisplayOrder($olmap, $oldOrder, $newOrder);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$olmap_id.'#olmap-ollayers');
    }
    
}