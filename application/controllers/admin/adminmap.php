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

class Adminmap extends MY_Controller {

    protected $msmapctrlpath;
    protected $olmapctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->msmapctrlpath = 'admin/adminmsmap';
        $this->olmapctrlpath = 'admin/adminolmap';
        
        $this->load->model('map_model');
        $this->load->model('rating/rating_model');
    }
    
    /**
     * Action index
     * Display a list of maps
     */
    public function index()
    {
        // Load main content
        $items = $this->map_model->loadAll();
        $data = array(
            'items' => $items,
            'map' => $this->map_model->create(),
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        $content = $this->load->view('admin/map/adminmap', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of the map
     * @param string $id 
     */
    public function edit($id)
    {   
        // Load main content
        $bean = $this->map_model->load($id);
        $data = array(
            'map' => $bean,
            'ctrlpath' => $this->ctrlpath,
            'msmapctrlpath' => $this->msmapctrlpath,
            'olmapctrlpath' => $this->olmapctrlpath,
            'action' => '/save/'.$bean->id);
        $content = $this->load->view('admin/map/admineditmap', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of mapserver units
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
                $map = $this->map_model->create();
                $account = $this->account_model->load($this->session->userdata('username'));
                $map->owner = $account;
                
                // Especific new item validation
                $exists = $this->map_model->loadByAlias($post['alias']);
                if (!empty($exists)) throw new Exception('System name is in use!');
            }
            // Load existing map
            else {
                $map = $this->map_model->load($id);
                if (!$map) throw new Exception('Map not found!');
            }

            // General validation
            if (empty($post['title'])) throw new Exception('Invalid title!');
            if (empty($post['alias']) || strlen($post['alias']) < 3)
                throw new Exception('System name has to have at leas 3 characters!');
            
            // Save
            $fields = array('title', 'description', 'alias');
            $map->import($post, implode(',', $fields));
            $this->map_model->save($map);
            $id = $map->id;
            $info[] = 'The map was saved';
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load main content
        $bean = $this->map_model->load($id);
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'map' => $bean,
            'ctrlpath' => $this->ctrlpath,
            'msmapctrlpath' => $this->msmapctrlpath,
            'olmapctrlpath' => $this->olmapctrlpath,
            'action' => '/save/'.$bean->id);
        $content = $this->load->view('admin/map/admineditmap', $data, TRUE);

        // Render
        $this->render($content);
        
    }
    
    /**
     * Action delete
     * Deleted the selected map
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->map_model->delete($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath);
    }
    
}