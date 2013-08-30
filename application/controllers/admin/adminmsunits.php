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

class Adminmsunits extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
    }
    
    /**
     * Action index
     * Display a list of registered mapserver units
     */
    public function index()
    {   
        // Load all mapserver coordinate units
        // TODO: Pagination
        $items = $this->mapserver_model->loadUnitsAll();
        
        // Load main content
        $content = $this->load->view('admin/mapserver/adminunits', 
                array('items' => $items), TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of coordinate unit
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load units
            $bean = $this->mapserver_model->loadUnits($id);
            if (!$bean) throw new Exception('Unit not found!');
            
            // Load main content
            $data = array('msunits' => $bean);
            $content = $this->load->view('admin/mapserver/admineditunits', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
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
        try {
            // load post data
            $name = $this->input->post('name');
            
            // Create new mapserver units
            if ($id === 'new') {
                $msunits = $this->mapserver_model->createUnits('new');
                $account = $this->account_model->load($this->session->userdata('username'));
                $msunits->owner = $account;
            }
            // Load existing units
            else {
                $msunits = $this->mapserver_model->loadUnits($id);
                if (!$msunits) throw new Exception('Unit not found!');
            }

            // Validate data and save
            if (!empty($name)) {
                $exists = $this->database_model->findOne('msunits', ' name = ?', array($name));
                if (!empty($exists)) throw new Exception('Duplicated unit!');
                $msunits->name = $this->input->post('name');
                $this->mapserver_model->save($msunits);    
            }
            
            if (!$this->input->is_ajax_request())
                redirect(base_url().'admin/adminmsunits/edit/'.$msunits->id);
        }
        catch(Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        
    }
    
    /**
     * Action delete
     * Deleted the selected mapserver units
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->mapserver_model->deleteUnits($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminmsunits');
    }
    
}