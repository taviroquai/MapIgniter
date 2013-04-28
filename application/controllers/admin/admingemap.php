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

class Admingemap extends MY_Controller {

    protected $mapctrlpath;
    protected $gelayerctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('googleearth/googleearth_model');
        $this->load->model('map_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->mapctrlpath = 'admin/adminmap';
        $this->gelayerctrlpath = 'admin/admingelayer';
    }
    
    /**
     * Action index
     * Display a list of Google Earth maps
     */
    public function index()
    {   
        // Load all map
        // TODO: Pagination
        $items = $this->googleearth_model->loadMapAll();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        $content = $this->load->view('admin/googleearth/adminmap', $data, TRUE);
        
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
                $bean = $this->googleearth_model->createMap($map);
            }
            // Load map
            else {
                $bean = $this->googleearth_model->loadMap($id);
                if (!$bean) throw new Exception('Google Earth map not found!');
            }
            
            // Load main content
            $data = array(
                'gemap' => $bean,
                'ctrlpath' => $this->ctrlpath,
                'gelayerctrlpath' => $this->gelayerctrlpath,
                'gelayers' => $this->googleearth_model->loadLayerAll());
            if ($id === 'new') $data['action'] = '/save/new';
            else $data['action'] = '/save/'.$bean->id;
            $content = $this->load->view('admin/googleearth/admineditmap', $data, TRUE);
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
            
            // Create new map
            if ($id === 'new') {
                $map = $this->map_model->load($post['map_id']);
                if (empty($map)) throw new Exception('Map not found');
                $gemap = $this->googleearth_model->createMap($map);
                $account = $this->account_model->load($this->session->userdata('username'));
                $gemap->owner = $account;
            }
            // Load existing map
            else {
                $gemap = $this->googleearth_model->loadMap($id);
                if (!$gemap) throw new Exception('Google Earth map not found!');
            }
            
            // Set new data and save
            $gemap->import($post);
            $this->googleearth_model->save($gemap);
            $info[] = 'The Google Earth map was saved';
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'gemap' => $gemap,
            'ctrlpath' => $this->ctrlpath,
            'gelayerctrlpath' => $this->gelayerctrlpath,
            'action' => '/save/'.$gemap->id,
            'gelayers' => $this->googleearth_model->loadLayerAll());
        $content = $this->load->view('admin/googleearth/admineditmap', $data, TRUE);
        $this->render($content);
        
    }
    
    /**
     * Action delete
     * Deleted the selected map
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->googleearth_model->deleteMap($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->mapctrlpath);
    }
    
    /**
     * Action addlayer
     * Adds selected layers to the map
     */
    public function addlayer($gemap_id)
    {
        $gemap = $this->googleearth_model->loadMap($gemap_id);
        if (empty($gemap)) return;
        $selected = $this->input->post('selected');
        foreach ($selected as $gelayer_id) {
            $gelayer = $this->googleearth_model->loadLayer($gelayer_id);
            if (empty($gelayer)) continue;
            $this->googleearth_model->addMapLayer($gemap, $gelayer);
        }
        
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$gemap_id);
    }
    
    /**
     * Action dellayer
     * Removes selected layers from map
     */
    public function dellayer($gemap_id)
    {
        $gemap = $this->googleearth_model->loadMap($gemap_id);
        if (empty($gemap)) return;
        $selected = $this->input->post('selected');
        $this->googleearth_model->delMapLayer($gemap, $selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$gemap_id);
    }
       
}