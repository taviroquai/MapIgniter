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

class Postgis extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/postgis_model');
        $this->load->model('layer_model');
        $this->load->model('rating/rating_model');
        
        $this->getfeatureview = 'postgis/showpgplace';
    }
    
    public function index()
    {
        
    }
    
    public function getfeature($id, $layeralias) {
        
        // Data to the layout
        $data = array();
                    
        try {
            // Load Layer
            $layer = $this->layer_model->loadByAlias($layeralias);
            if (!$layer) throw new Exception('Layer not found!');
            
            // Load table
            $pglayer = $this->database_model->findOne('pglayer', ' layer_id = ? ', array($layer->id));
            if (!$pglayer) throw new Exception('Postgis layer not found');
            
            $table = $this->postgis_model->loadTable($pglayer->pgplacetype);
            if (!$table) throw new Exception('Postgis table not found!');
            
            $record = $this->postgis_model->loadRecords($table, ' gid = ? ', array($id), 1);
            if (empty($record)) throw new Exception('Place not found!');
            $record = reset($record);
            unset($record['the_geom']);
            
            // Load main content
            $data = array(
                'layeralias' => $layeralias,
                'table' => $table,
                'sysfields' => $this->postgis_model->getExcludeFields(),
                'record' => $record);
            
            // Add rating
            $ratingitems = array($layeralias.'.'.$record['gid']);
            $data['rating'] = 
            $this->rating_model->loadAll($ratingitems, 'pgplace', $this->account, $this->input->ip_address());
            
            // Load default view
            // To create custom get feature views, please extend this controller
            // and create a custom view (change getfeatureview)
            $content = $this->load->view($this->getfeatureview, $data, TRUE);
            
            if (!empty($record['title'])) $data['pagetitle'] = $record['title'];
            if (!empty($record['description'])) $data['pagedescription'] = strip_tags ($record['description']);
            
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->layout = 'module';
        $this->render($content, $data);
    }
    
    /**
     * Action getfeaturejson
     * Loas a postgis record and outputs
     * @param string $pglayer_id Postgis layer ID
     * @param string $id Feature ID
     */
    public function getfeaturejson($pglayer_id, $id)
    {   
        try {
            // Load postgis layer
            $pglayer = $this->postgis_model->loadLayer($pglayer_id);
            $tablename = $pglayer->pgplacetype;
            
            // Load table
            $table = $this->postgis_model->loadTable($tablename);
            if (!$table) throw new Exception('Postgis table not found!');
            
            $record = $this->postgis_model->loadRecords($table, ' gid = ? ', array($id), 1);
            if (empty($record)) throw new Exception('Place not found!');
            $record = reset($record);
            unset($record['the_geom']);

            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'record' => $record);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        $this->output->set_header('Cache-Control: no-cache, must-revalidate');
        $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        $this->output->set_content_type('text/json');
        $this->output->set_output(json_encode($data, TRUE));
    }
    
}