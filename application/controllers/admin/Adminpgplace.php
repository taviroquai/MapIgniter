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

class Adminpgplace extends MY_Controller {

    protected $listpgplaceview;
    protected $fullscreenctrl;
    protected $dataexplorerctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/postgis_model');
        $this->load->model('layer_model');
        $this->load->model('rating/rating_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->listpgplaceview = 'admin/place/adminpgplacerecords';
        $this->fullscreenctrl = 'admin/fullscreenpgplace';
        $this->dataexplorerctrlpath = 'admin/dataexplorer';
    }
    
    /**
     * Action index    
     * Display a list of place types (tables)
     */
    public function index()
    {   
        // Load all tables
        // TODO: Pagination
        $items = $this->postgis_model->loadLayerAll();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath);
        $content = $this->load->view('admin/place/adminpgplace', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action index    
     * Display a list of all postgis table records
     */
    public function listitems($id)
    {   
        // Load all records
        // TODO: Pagination
        $_SESSION['list']['pgplacewhere'] = 
            empty($_SESSION['list']['pgplacewhere']) ? ' true = ?' : $_SESSION['list']['pgplacewhere'];
        $_SESSION['list']['pgplacevalues'] = 
            empty($_SESSION['list']['pgplacevalues']) ? array('true') : $_SESSION['list']['pgplacevalues'];
        $_SESSION['list']['pgplacelimit'] = 
            empty($_SESSION['list']['pgplacelimit']) ? 10 : $_SESSION['list']['pgplacelimit'];
        $olmap = null;
        
        $post = $this->input->post();
        if (!empty($post['filter'])) $_SESSION['list']['pgplacewhere'] = $post['filter'];
        if (!empty($post['values'])) {
            $values = explode(';',$post['values']);
            foreach ($values as &$value) $value = trim($value);
            $_SESSION['list']['pgplacevalues'] = $values;
        }
        if (!empty($post['limit'])) {
            $_SESSION['list']['pgplacelimit'] = $post['limit'];
        }
        try {
            $pglayer = $this->postgis_model->loadLayer($id);
            $tablename = $pglayer->pgplacetype;
            $table = $this->postgis_model->loadTable($tablename);
            $items = $this->postgis_model->loadRecords(
                $table,
                $_SESSION['list']['pgplacewhere'],
                $_SESSION['list']['pgplacevalues'],
                $_SESSION['list']['pgplacelimit']
            );
            
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
            'filter' => $_SESSION['list']['pgplacewhere'],
            'values' => implode(';', $_SESSION['list']['pgplacevalues']),
            'limit' => $_SESSION['list']['pgplacelimit'],
            'limitopts' => $this->postgis_model->optsRecordsPerPage(),
            'olmap' => $olmap,
            'editlayerindex' => $editlayerindex,
            'fullscreenctrl' => $this->fullscreenctrl,
            'ctrlpath' => $this->ctrlpath);
        if (!empty($this->pgplacectrl)) $data['pgplacectrl'] = $this->pgplacectrl;
        $content = $this->load->view($this->listpgplaceview, $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a postgis record
     * @param string $id 
     */
    public function edit($pglayer_id, $id)
    {   
        try {
            // Load postgis layer
            $pglayer = $this->postgis_model->loadLayer($pglayer_id);
            $tablename = $pglayer->pgplacetype;
            
            // Load table
            $table = $this->postgis_model->loadTable($tablename);
            if (!$table) throw new Exception('Postgis table not found!');
            
            // Create new mapserver layer
            if ($id === 'new') {
                $record = $this->postgis_model->createRecord($table);
            }
            // Load layer
            else {
                $record = $this->postgis_model->loadRecords($table, ' gid = ? ', array($id), 1);
                if (empty($record)) throw new Exception('Postgis place not found!');
                $record = reset($record);
                unset($record['the_geom']);
            }
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
                'pglayer' => $pglayer,
                'table' => $table,
                'sysfields' => $this->postgis_model->getExcludeFields(),
                'record' => $record);
            $data['action'] = ($id === 'new') ? "/save/{$pglayer->id}/new" : "/save/{$pglayer->id}/".$record['gid'];
            $content = $this->load->view('admin/place/admineditpgplace', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
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
        $post = $this->input->post();
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
                $record = $this->postgis_model->loadRecords($table, ' gid = ? ', array($id), 1);
                if (empty($record)) throw new Exception('Place not found!');
                $record = reset($record);
                unset($record['the_geom']);
            }
            
            // TODO: Validate data
            
            // Import post values
            $sysfields = $this->postgis_model->getExcludeFields();
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
            
            
        }
        catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
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
                'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
                'record' => $record);

            // Render
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json');
            echo json_encode($data, TRUE);
            return;
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
            if (!$table) throw new Exception('Postgis table not found!');
            
            // Create new postgis record
            if ($id === 'new') {
                $record = $this->postgis_model->createRecord($table);
            }
            // Load postgis record
            else {
                $record = $this->postgis_model->loadRecords($table, ' gid = ? ', array($id), 1);
                if (empty($record)) throw new Exception('Place not found!');
                $record = reset($record);
                unset($record['the_geom']);
            }
            
            // TODO: Validate data
            $proj = (int) str_replace('EPSG:', '', $post['proj']);
            
            // Save record
            $record = $this->postgis_model->saveGeometry($table, $record, $post['wkt'], $proj, $post['format']);
            $info[] = 'Place was saved';
            
            // Load main content
            $data = array(
                'msgs' => array('errors' => $errors, 'info' => $info),
                'ctrlpath' => $this->ctrlpath,
                'record' => $record);
            
        }
        catch (Exception $e) {
            $errors[] = $e->getMessage();
            $data = array(
                'msgs' => array('errors' => $errors, 'info' => $info)
                );
        }
        
        // Render
        $this->output->set_header('Cache-Control: no-cache, must-revalidate');
        $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data, TRUE));
    }
    
    /**
     * Action ajaxloadplace
     * Loas a postgis record and outputs
     * @param string $tablename
     * @param string $id 
     */
    public function ajaxloadplace($pglayer_id, $id, $srs = null)
    {   
        try {
            // Load postgis layer
            $pglayer = $this->postgis_model->loadLayer($pglayer_id);
            $tablename = $pglayer->pgplacetype;
            
            // Load table
            $table = $this->postgis_model->loadTable($tablename);
            if (!$table) throw new Exception('Postgis table not found!');
            
            // Prepare geometry transformations
            if (!empty($srs)) $srid = str_replace('EPSG:', '', $srs);
            else $srid = $table->srid;
            
            // Load records
            $record = $this->postgis_model->loadRecords($table, ' gid = ? ', array($id), 1, $srid);
            if (empty($record)) throw new Exception('Place not found!');
            $record = reset($record);
            unset($record['the_geom']);

            // Load main content
            $data = array(
                'success' => true,
                'ctrlpath' => $this->ctrlpath,
                'record' => $record);
        }
        catch (Exception $e) {
            $data = array(
                'success' => false,
                'message' => $e->getMessage());
        }
        
        $this->output->set_header('Cache-Control: no-cache, must-revalidate');
        $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        $this->output->set_content_type('text/json');
        $this->output->set_output(json_encode($data, TRUE));
    }
    
    /**
     * Action delete
     * Deleted the selected records
     */
    public function delete($pglayer_id)
    {
        // Load postgis layer
        $pglayer = $this->postgis_model->loadLayer($pglayer_id);
        $tablename = $pglayer->pgplacetype;
        
        // Load table
        $table = $this->postgis_model->loadTable($tablename);
        if (!$table) throw new Exception('Postgis table not found!');
            
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->postgis_model->deleteRecords($table, $selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/listitems/'.$pglayer->id);
    }
    
}