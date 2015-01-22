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

class Adminmslayer extends MY_Controller {

    protected $msclassctrlpath;
    protected $dataexplorerctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
        $this->load->model('layer_model');
        $this->load->model('database/postgis_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->msclassctrlpath = 'admin/adminmsclass';
        $this->dataexplorerctrlpath = 'admin/dataexplorer';
    }
    
    /**
     * Action index    
     * Display a list of mapserver layers
     */
    public function index()
    {   
        // Load all layers
        // TODO: Pagination
        $items = $this->mapserver_model->loadLayerAll();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        $content = $this->load->view('admin/mapserver/adminlayer', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a layer
     * @param string $id 
     */
    public function edit($id, $layer_id = null)
    {   
        try {
            
            // Create new mapserver layer
            if ($id === 'new') {
                $layer = $this->layer_model->load($layer_id);
                if (empty($layer)) throw new Exception ('MapServer layer not found!');
                $bean = $this->mapserver_model->createLayer($layer);
            }
            // Load layer
            else {
                $bean = $this->mapserver_model->loadLayer($id);
                if (!$bean) throw new Exception('MapServer layer not found!');
                
                if ($bean->mslayerconntype->name == 'postgis') {
                    $datatable = $this->postgis_model->getExternalTable($bean);
                }
            }
            
            // Load types of connection
            $mslayerconntypes = $this->mapserver_model->loadLayerConnectionTypeAll();
            
            // Load types of connection
            $msunits = $this->mapserver_model->loadUnitsAll();
            
            // Load types of connection
            $mslayertypes = $this->mapserver_model->loadLayerTypeAll();
            
            // Load mapserver metadata
            $msmetadata = $this->mapserver_model->loadMetadataAll();
            
            // Temp layer class item
            $msclass = $this->mapserver_model->createClass($bean, 'nova_classe1');
            
            // Correct projection params for textarea
            $proj_params = explode(" ", $bean->projection);
            foreach ($proj_params as &$param) $param = trim($param);
            $bean->projection = implode("\n", $proj_params);
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'msclassctrlpath' => $this->msclassctrlpath,
                'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
                'mslayer' => $bean,
                'mslayertypes' => $mslayertypes,
                'mslayerconntypes' => $mslayerconntypes,
                'msunits' => $msunits,
                'msmetadata' => $msmetadata,
                'msclass' => $msclass);
            if (!empty($datatable)) $data['datatable'] = $datatable;
            $data['action'] = ($id === 'new') ? '/save/new' : '/save/'.$bean->id;
            if ($id === 'new') $data['pgplacetype'] = $this->postgis_model->loadAllTables();
            
            $content = $this->load->view('admin/mapserver/admineditlayer', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of mapserver layer
     * @param string $id 
     */
    public function save($id)
    {   
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new mapserver layer
            if ($id === 'new') {
                $layer = $this->layer_model->load($post['layer_id']);
                if (empty($layer)) throw new Exception('MapServer layer not found');
                $mslayer = $this->mapserver_model->createLayer($layer);
                $mslayer->owner = $this->account;
                
                // Checks for internal postgis data source
                if (!empty($post['pgplacetype'])) {
                    $table = $this->postgis_model->loadTable($post['pgplacetype']);
                    if ($table) {
                        $dbconfig = $this->database_model->getConfig('userdata');
                        $post['mslayerconntype_id'] = 5;
                        $post['connection'] = "host={$dbconfig['hostname']} user={$dbconfig['username']} password={$dbconfig['password']} dbname={$dbconfig['database']}";
                        $post['data'] = "the_geom FROM {$post['pgplacetype']} USING UNIQUE gid USING srid={$table->srid}";
                        $post['projection'] = "init=epsg:{$table->srid}";
                        switch ($table->srid) {
                            case 4326: $post['extent'] = '-180 -90 180 90'; break;
                            case 900913:
                            case 3857: $post['extent'] = '-20037508.34 -20037508.34 20037508.34 20037508.34'; break;
                        }
                        $post['status'] = 'on';
                        $post['dump'] = 'true';
                        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(6), 'value' => 'all');
                        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(8), 'value' => 'EPSG:'.$table->srid);
                        $addclass[] = array('name' => 'myclass');
                    }
                }
            
            }
            // Load existing style
            else {
                $mslayer = $this->mapserver_model->loadLayer($id);
                if (!$mslayer) throw new Exception('MapServer layer not found!');
            }

            // TODO: Validate data
            
            $fields = array(
                'pgplacetype',
                'extent',
                'projection',
                'connection',
                'dump',
                'status',
                'opacity',
                'symbolscaledenom',
                'maxscaledenom',
                'minscaledenom',
                'labelitem',
                'classitem',
                'template',
                'data'
            );
            
            // Correct projection params for input
            $proj_params = explode("\n", $post['projection']);
            foreach ($proj_params as &$param) $param = trim($param);
            $post['projection'] = implode(" ", $proj_params);
            
            $mslayer->import($post, implode(',', $fields));
            $mslayer->layer = $this->layer_model->load($post['layer_id']);
            $mslayer->msunits = $this->mapserver_model->loadUnits($post['msunits_id']);
            $mslayer->mslayerconntype = $this->mapserver_model->loadLayerConnectionType($post['mslayerconntype_id']);
            $mslayer->mslayertype = $this->mapserver_model->loadLayerType($post['mslayertype_id']);
            $this->mapserver_model->save($mslayer);
            $info[] = 'MapServer layer was saved';
            
            // Add metadata items
            if (!empty($addmetadata)) {
                foreach ($addmetadata as $item) {
                    $this->mapserver_model->addLayerMetadata($mslayer, $item['metadata'], $item['value']);
                    $info[] = 'Metadata '.$item['metadata']->name.' item was created and added';
                }
            }
            
            // Add a features class
            if (!empty($addclass)) {
                foreach ($addclass as $item) {
                    $msclass = $this->mapserver_model->createClass($mslayer, $item['name']);
                    $msclass->owner = $this->account;
                    $this->mapserver_model->save($msclass);
                    $info[] = 'Class '.$item['name'].' was created and added';
                }
            }
        }
        catch(Exception $e) {
            show_error($e->getMessage());
        }
        
        // Load types of connection
        $mslayerconntypes = $this->mapserver_model->loadLayerConnectionTypeAll();

        // Load types of connection
        $msunits = $this->mapserver_model->loadUnitsAll();

        // Load types of connection
        $mslayertypes = $this->mapserver_model->loadLayerTypeAll();

        // Load mapserver metadata
        $msmetadata = $this->mapserver_model->loadMetadataAll();

        // Temp layer class item
        $msclass = $this->mapserver_model->createClass($mslayer, 'nova_classe1');

        // Correct projection params for textarea
        $proj_params = explode(" ", $mslayer->projection);
        foreach ($proj_params as &$param) $param = trim($param);
        $mslayer->projection = implode("\n", $proj_params);
        
        if ($mslayer->mslayerconntype->name == 'postgis') {
            $this->load->model('database/postgis_model');
            $datatable = $this->postgis_model->getExternalTable($mslayer);
        }

        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'msclassctrlpath' => $this->msclassctrlpath,
            'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
            'mslayer' => $mslayer,
            'mslayertypes' => $mslayertypes,
            'mslayerconntypes' => $mslayerconntypes,
            'msunits' => $msunits,
            'msmetadata' => $msmetadata,
            'msclass' => $msclass,
            'action' => '/save/'.$mslayer->id);
        if (!empty($datatable)) $data['datatable'] = $datatable;
        $content = $this->load->view('admin/mapserver/admineditlayer', $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action delete
     * Deleted the selected mapserver layer
     */
    public function delete($msmapfile_id)
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->mapserver_model->deleteLayer($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$msmapfile_id);
    }
    
    /**
     * Action savemetadata
     * Saves the new data to the layer metadata item
     * @param mixed $id
     * @param integer $mslayer_id 
     */
    public function savemetadata($id, $mslayer_id = null)
    {   
        try {
            // Load post data
            $value = $this->input->post('value');
            $msmetadata_id = $this->input->post('msmetadata_id');
            
            // Load Metadata Item
            $msmetadata = $this->mapserver_model->loadMetadata($msmetadata_id);
            
            // Save layer metadata item
            if ($id === 'new') {
                // Load Mapserver Layer
                $mslayer = $this->mapserver_model->loadLayer($mslayer_id);
                if (!$mslayer) throw new Exception('MapServer layer not found!');
                $this->mapserver_model->addLayerMetadata($mslayer, $msmetadata, $value);
            }
            else {
                $mslayermd = $this->mapserver_model->addLoadLayerMetadata($id);
                $mslayermd->value = $value;
                $this->mapserver_model->save($mslayermd);
            }
        }
        catch (Exception $e) {
            show_error($e->getMessage());
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$mslayer->id);
    }
    
    /**
     * Action delete layer metadata
     */
    public function delmetadata($mslayer_id)
    {
        $selected = $this->input->post('selected');
        $this->mapserver_model->deleteLayerMetadata($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$mslayer_id);
    }
    
}