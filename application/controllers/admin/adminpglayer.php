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

class Adminpglayer extends MY_Controller {

    protected $dataexplorerctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/postgis_model');
        $this->load->model('layer_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
    }
    
    /**
     * Action index    
     * Display a list of place types (tables)
     */
    public function index()
    {   
        // Load all tables
        // TODO: Pagination
        $items = $this->postgis_model->loadAllTables();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath,
            'table' => $this->postgis_model->createTable(),
            'srid_list' => $this->postgis_model->loadAllSRID(),
            'geom_types' => $this->postgis_model->loadAllGeomTypes(),
            'action' => '/save/new');
        $content = $this->load->view('admin/place/adminpgplacetype', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a postgis table
     * @param string $id 
     */
    public function edit($id, $layer_id = null)
    {   
        try {
            
            // Create new postgis layer
            if ($id === 'new') {
                $layer = $this->layer_model->load($layer_id);
                if (!$layer) throw new Exception('Layer not found!');
                $pglayer = $this->postgis_model->createLayer($layer);
                $table = null;
            }
            // Load layer
            else {
                $pglayer = $this->postgis_model->loadLayer($id);
                if (!$pglayer) throw new Exception('Postgis layer not found!');
                $table = $this->postgis_model->loadTable($pglayer->pgplacetype);
            }
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'pglayer' => $pglayer,
                'table' => $table,
                'tables' => $this->postgis_model->loadAllTables(),
                'srid_list' => $this->postgis_model->loadAllSRID(),
                'geom_types' => $this->postgis_model->loadAllGeomTypes(),
                'attrtypes' => $this->postgis_model->attributeTypes());
            $data['action'] = ($id === 'new') ? '/save/new' : '/save/'.$pglayer->id;
            $content = $this->load->view('admin/place/admineditpglayer', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of postgis layer
     * @param string $name
     */
    public function save($id)
    {   
        $errors = array();
        $info = array();
        
        $fields = array('srid','type','pgplacetype');
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new postgis layer
            if ($id === 'new') {
                $layer = $this->layer_model->load($post['layer_id']);
                if (!$layer) throw new Exception('Layer not found!');
                $pglayer = $this->postgis_model->createLayer($layer);
                $pglayer->owner = $this->account;
                $table = $this->postgis_model->createTable($pglayer->pgplacetype);
            }
            // Load layer
            else {
                $pglayer = $this->postgis_model->loadLayer($id);
                if (!$pglayer) throw new Exception('Postgis layer not found!');
                $table = $this->postgis_model->loadTable($pglayer->pgplacetype);
            }

            // Import post data
            if ($post['pgplacetype'] == 'new_pgplacetype') {
                $post['pgplacetype'] = $post['new_pgplacetype'];
                $table = $this->postgis_model->createTable($post['pgplacetype']);
                $tablefields = array(
                    'srid' => $post['srid'],
                    'type' => $post['type']);
                $this->postgis_model->import($table, $tablefields, 'srid,type');
                $this->postgis_model->saveTable($table);
            }
                
            $pglayer->import($post, implode(',', $fields));
            $this->postgis_model->saveLayer($pglayer);
            $info[] = 'Postgis layer was saved';
            
            $tablefields = array(
                'srid' => $pglayer->srid,
                'type' => $pglayer->type,
                'name' => $pglayer->pgplacetype);
            $this->postgis_model->import($table, $tablefields, 'srid,type');
            $this->postgis_model->saveTable($table);
            $info[] = 'Postgis table changes were saved';
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }

        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'pglayer' => $pglayer,
            'table' => $table,
            'tables' => $this->postgis_model->loadAllTables(),
            'srid_list' => $this->postgis_model->loadAllSRID(),
            'geom_types' => $this->postgis_model->loadAllGeomTypes(),
            'attrtypes' => $this->postgis_model->attributeTypes(),
            'action' => '/save/'.$pglayer->id);
        $content = $this->load->view('admin/place/admineditpglayer', $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action delete
     * Deleted the selected postgis layer
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->postgis_model->deleteLayer($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath);
    }
    
    public function saveattribute($id, $pglayer_id = '') {
        
        $errors = array();
        $info = array();
        
        $post = $this->input->post(NULL, TRUE);
        try {
            
            $pglayer = $this->postgis_model->loadLayer($pglayer_id);
            if (!$pglayer) throw new Exception('Postgis layer not found!');
            if ($id !== 'new') throw new Exception('Only available to new attributes');
            
            // Load table
            $table = $this->postgis_model->loadTable($pglayer->pgplacetype);
            if (!$table) throw new Exception('Postgis table not found!');
            
            // Validate data
            foreach ($table->attributes as $field => $value) {
                if ($post['name'] == $field) throw new Exception('.');
            }
            
            $result = $this->postgis_model->addColumn($table, $post['name'], $post['type']);
            $table->attributes[$post['name']] = $post['type'];
        }
        catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'pglayer' => $pglayer,
            'table' => $table,
            'srid_list' => $this->postgis_model->loadAllSRID(),
            'geom_types' => $this->postgis_model->loadAllGeomTypes(),
            'attrtypes' => $this->postgis_model->attributeTypes(),
            'action' => '/save/'.$table->name);
        $content = $this->load->view('admin/place/admineditpglayer', $data, TRUE);
        $this->render($content);
    }
    
    public function delattribute($pglayer_id) {
        $pglayer = $this->postgis_model->loadLayer($pglayer_id);
        if (!$pglayer) throw new Exception('Postgis layer not found!');
        $selected = $this->input->post('selected');
        $table = $this->postgis_model->loadTable($pglayer->pgplacetype);
        if ($table) { 
            if (!empty($selected)) $this->postgis_model->deleteFields($table, $selected);
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$pglayer->id.'#editattributes');
    }
    
}