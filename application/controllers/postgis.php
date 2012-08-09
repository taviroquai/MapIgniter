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
            
            switch($table->name) {
                case 'localmundo':
                    $content = $this->load->view('postgis/localmundo/showpgplace', $data, TRUE);
                    break;
                case 'castelos':
                    $content = $this->load->view('postgis/castelos/showpgplace', $data, TRUE);
                    break;
                default:
                    $content = $this->load->view('postgis/showpgplace', $data, TRUE);
            }
            
            if (!empty($record['title'])) $data['pagetitle'] = $record['title'];
            if (!empty($record['description'])) $data['pagedescription'] = $record['description'];
            
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
     * @param string $tablename
     * @param string $id 
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
        
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: text/json');
        echo json_encode($data, TRUE);
    }
    
}