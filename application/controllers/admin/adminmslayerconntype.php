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

class Adminmslayerconntype extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
    }
    
    /**
     * Action index
     * Display a list of mapserver data connection types
     */
    public function index()
    {   
        // Load all mapserver data connection types
        // TODO: Pagination
        $items = $this->mapserver_model->loadLayerConnectionTypeAll();
        
        // Load main content
        $content = $this->load->view('admin/mapserver/adminconntype', 
                array('items' => $items), TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a type of data connection
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load data connection type
            $bean = $this->mapserver_model->loadLayerConnectionType($id);
            if (!$bean) throw new Exception('Connection type not found!');
            
            // Load main content
            $data = array('mslayerconntype' => $bean);
            $content = $this->load->view('admin/mapserver/admineditconntype', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of mapserver data connection type
     * @param string $id 
     */
    public function save($id)
    {   
        try {
            // load post data
            $name = $this->input->post('name');
            
            // Create new mapserver data connection type
            if ($id === 'new') {
                $mslayerconntype = $this->mapserver_model->createLayerConnectionType('new');
                $account = $this->account_model->load($this->session->userdata('username'));
                $mslayerconntype->owner = $account;
            }
            // Load existing data connection type
            else {
                $mslayerconntype = $this->mapserver_model->loadLayerConnectionType($id);
                if (!$mslayerconntype) throw new Exception('Connection type not found!');
            }

            // Validate data and save
            if (!empty($name)) {
                $exists = $this->database_model->findOne('mslayerconntype', ' name = ?', array($name));
                if (!empty($exists)) throw new Exception('Duplicated connection type!');
                $mslayerconntype->name = $this->input->post('name');
                $this->mapserver_model->save($mslayerconntype);    
            }
            
            if (!$this->input->is_ajax_request())
                redirect(base_url().'admin/adminmslayerconntype/edit/'.$mslayerconntype->id);
        }
        catch(Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        
    }
    
    /**
     * Action delete
     * Deleted the selected mapserver data connection type
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->mapserver_model->deleteLayerConnectionType($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminmslayerconntype');
    }
    
}