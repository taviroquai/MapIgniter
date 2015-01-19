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

require_once APPPATH.'controllers/admin/adminpgplace.php';

class Managepgplace extends Adminpgplace {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/postgis_model');
        $this->load->model('layer_model');
        
        $this->layout = 'registered';
        $this->ctrlpath = 'user/'.$this->router->fetch_class();
        $this->listpgplaceview = 'admin/place/adminpgplacerecords';
        $this->fullscreenctrl = 'user/managefullscreenpgplace';
        $this->dataexplorerctrlpath = 'user/userdataexplorer';
    }
    
    /**
     * Action index    
     * Display a list of postgis layers
     */
    public function index()
    {   
        // Load all tables
        // TODO: Pagination
        $items = $this->postgis_model->loadLayerAll();

        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath
        );
        
        // Add rating items
        if (!empty($items)) {
            foreach ($items as $item) {
                $ratingitems[] = $item->id;
            }
            $data['rating'] = 
            $this->rating_model->loadAll($ratingitems, 'pglayer', $this->account, $this->input->ip_address());
        }
        
        $content = $this->load->view('user/place/ownpgplace', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action index    
     * Display a list of all table records
     */
    public function listitems($id)
    {   
        // Load all records
        // TODO: Pagination
        $where = ' true = ?';
        $values = array('true');
        $limit = 10;
        $olmap = null;
        
        $post = $this->input->post(NULL, TRUE);
        if (!empty($post['filter'])) $where = $post['filter'];
        if (!empty($post['values'])) {
            $values = explode(';',$post['values']);
            foreach ($values as &$value) $value = trim($value);
        }
        if (!empty($post['limit'])) $limit = $post['limit'];
        try {
            $pglayer = $this->postgis_model->loadLayer($id);
            $tablename = $pglayer->pgplacetype;
            $table = $this->postgis_model->loadTable($tablename);
            $items = $this->postgis_model->loadOwnerRecords($this->account->username, $table, $where, $values, $limit);
            
            // Load OpenLayers Map
            $this->load->model('openlayers/openlayers_model');
            $ollayers = $this->openlayers_model->findWMSLayer($tablename);
            $olmaps = $this->openlayers_model->findMapByLayers($ollayers);
            if (!empty($olmaps)) $olmap = reset($olmaps);
            $editlayerindex = $this->openlayers_model->findMapLayerIndexByPlaceType($olmap, $tablename);
            
        } catch (RedBean_Exception_SQL $e) {
            $this->database_model->selectDatabase();
            $items = array();
        }
        
        // Load main content
        $data = array(
            'pglayer' => $pglayer,
            'table' => $table,
            'items' => $items,
            'filter' => $where,
            'values' => implode(';', $values),
            'limit' => $limit,
            'limitopts' => $this->postgis_model->optsRecordsPerPage(),
            'olmap' => $olmap,
            'editlayerindex' => $editlayerindex,
            'fullscreenctrl' => $this->fullscreenctrl,
            'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
            'ctrlpath' => $this->ctrlpath);
        if (!empty($this->pgplacectrl)) $data['pgplacectrl'] = $this->pgplacectrl;
        
        // Load items rating
        $ratingitems = array();
        foreach ($items as $item)
        $ratingitems[] = $pglayer->layer->alias.'.'.$item['gid'];
        $data['rating'] = 
        $this->rating_model->loadAll($ratingitems, 'pgplace', $this->account, $this->input->ip_address());
        
        $content = $this->load->view($this->listpgplaceview, $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of postgis record
     * @param string $id
     */
    public function save($pglayer_id, $id)
    {   
        $errors = array();
        $info = array();
        $post = $this->input->post(NULL);
        
        $sysfields = $this->postgis_model->getExcludeFields();
        
        try {
            // Load postgis layer
            $pglayer = $this->postgis_model->loadLayer($pglayer_id);
            $tablename = $pglayer->pgplacetype;
            
            // Load table
            $table = $this->postgis_model->loadTable($tablename);
            if (!$table) throw new Exception('Postgis table not found!');
            
            // Create new postgis record
            if ($id === 'new') {
                $record = $this->postgis_model->createRecord($table);
                
            }
            // Load postgis record
            else {
                $record = $this->postgis_model->loadOwnerRecords($this->account->username, $table, ' gid = ? ', array($id), 1);
                if (empty($record)) throw new Exception('Access denied to edit place!');
                $record = reset($record);
                unset($record['the_geom']);
            }
            
            // TODO: Validate data
            
            // Import post values
            
            foreach ($table->attributes as $field => $type) {
                if (in_array($field, $sysfields)) continue;
                if ($field == 'alias') $post[$field] = url_title($post[$field], 'dash', true);
                $record[$field] = $post[$field];
            }
            
            if (in_array('last_update', array_keys($record))) $record['last_update'] = date('Y-m-d H:i:s');
            if (in_array('owner', array_keys($record))) $record['owner'] = $this->account->username;
            
            // Save record
            $record = $this->postgis_model->saveRecord($table, $record, $this->account->username);
            $info[] = 'The place was saved';
            
            // Load main content
            $data = array(
                'msgs' => array('errors' => $errors, 'info' => $info),
                'ctrlpath' => $this->ctrlpath,
                'fullscreenctrl' => $this->fullscreenctrl,
                'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
                'pglayer' => $pglayer,
                'table' => $table,
                'sysfields' => $sysfields,
                'record' => $record,
                'action' => "/save/{$pglayer->id}/".$record['gid']);
            $content = $this->load->view('admin/place/admineditpgplace', $data, TRUE);

            // Render
            if ($this->input->is_ajax_request()) {
                $data = array(
                    'msgs' => array('errors' => $errors, 'info' => $info),
                    'ctrlpath' => $this->ctrlpath,
                    'record' => $record);

                // Render
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Content-type: application/json');
                echo json_encode($data, TRUE);
                return;
            }
            
        }
        catch (Exception $e) {
            $errors[] = $e->getMessage();
            
            // Load main content
            $data = array(
                'msgs' => array('errors' => $errors, 'info' => $info),
                'ctrlpath' => $this->ctrlpath,
                'table' => null,
                'sysfields' => $sysfields,
                'record' => null,
                'action' => "/listitems/$tablename/");
            $content = $this->load->view('admin/place/admineditpgplace', $data, TRUE);

            // Render
            if ($this->input->is_ajax_request()) {
                $data = array(
                    'msgs' => array('errors' => $errors, 'info' => $info),
                    'ctrlpath' => $this->ctrlpath,
                    'record' => null);

                // Render
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Content-type: application/json');
                echo json_encode($data, TRUE);
                return;
            }
        }
        
        $this->render($content);
    }
    
    /**
     * Action savewkt
     * Saves the geometry from OpenLayers feature
     * @param string $tablename
     * @param string $id
     */
    public function savegeom($pglayer_id, $id)
    {   
        $errors = array();
        $info = array();
        $post = $this->input->post(NULL, TRUE);
        
        try {
            // Load postgis layer
            $pglayer = $this->postgis_model->loadLayer($pglayer_id);
            $tablename = $pglayer->pgplacetype;
            
            // Load table
            $table = $this->postgis_model->loadTable($tablename);
            if (!$table) throw new Exception('O tipo de local não existe!');
            
            // Create new postgis record
            if ($id === 'new') {
                $record = $this->postgis_model->createRecord($table);
            }
            // Load postgis record
            else {
                $record = $this->postgis_model->loadOwnerRecords($this->account->username, $table, ' gid = ? ', array($id), 1);
                if (empty($record)) throw new Exception('Acess denied to edit place!');
                $record = reset($record);
                unset($record['the_geom']);
            }
            
            // TODO: Validate data
            $proj = (int) str_replace('EPSG:', '', $post['proj']);
            
            // Save record
            $record = $this->postgis_model->saveGeometry($table, $record, $post['wkt'], $proj, $post['format']);
            $info[] = 'The place was saved';
            
            
        }
        catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'record' => $record);
        
        // Render
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode($data, TRUE);
    }
    
}