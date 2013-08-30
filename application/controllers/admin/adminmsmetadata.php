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

class Adminmsmetadata extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
    }
    
    /**
     * Action index
     * Display a list of registered mapserver metadata items
     */
    public function index()
    {   
        // Load all mapserver metadata items
        // TODO: Pagination
        $items = $this->mapserver_model->loadMetadataAll();
        
        // Load main content
        $content = $this->load->view('admin/mapserver/adminmetadata', 
                array('items' => $items), TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a metadata item
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load metadata item
            $bean = $this->mapserver_model->loadMetadata($id);
            if (!$bean) throw new Exception('Metadata item not found!');
            
            // Load main content
            $data = array('msmetadata' => $bean);
            $content = $this->load->view('admin/mapserver/admineditmetadata', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data for the metadata item
     * @param string $id 
     */
    public function save($id)
    {   
        try {
            // load post data
            $name = $this->input->post('name');
            
            // Create new mapserver metadata item
            if ($id === 'new') {
                $msmetadata = $this->mapserver_model->createMetadata('new');
                $account = $this->account_model->load($this->session->userdata('username'));
                $msmetadata->owner = $account;
            }
            // Load existing metadata item
            else {
                $msmetadata = $this->mapserver_model->loadMetadata($id);
                if (!$msmetadata) throw new Exception('Metadata item not found!');
            }

            // Validate data and save
            if (!empty($name)) {
                $exists = $this->database_model->findOne('msmetadata', ' name = ?', array($name));
                if (!empty($exists)) throw new Exception('Duplicate metatada item!');
                $msmetadata->name = $this->input->post('name');
                $this->mapserver_model->save($msmetadata);    
            }
            
            if (!$this->input->is_ajax_request())
                redirect(base_url().'admin/adminmsmetadata/edit/'.$msmetadata->id);
        }
        catch(Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        
    }
    
    /**
     * Action delete
     * Deleted the selected metadata items
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->mapserver_model->deleteMetadata($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminmsmetadata');
    }
    
}