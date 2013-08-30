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

class Adminmsmap extends MY_Controller {

    protected $mslegendctrlpath;
    protected $mslayerctrlpath;
    protected $dataexplorerctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
        $this->load->model('map_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->mslegendctrlpath = 'admin/adminmslegend';
        $this->mslayerctrlpath = 'admin/adminmslayer';
        $this->dataexplorerctrlpath = 'admin/dataexplorer';
        
    }
    
    /**
     * Action index
     * Display a list of mapfiles
     */
    public function index()
    {   
        // Load all mapfiles
        // TODO: Pagination
        $items = $this->mapserver_model->loadMapfileAll();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        $content = $this->load->view('admin/mapserver/adminmap', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a mapfile
     * @param string $id 
     */
    public function edit($id, $map_id = null)
    {   
        try {
            
            // Create new mapfile
            if ($id === 'new') {
                $map = $this->map_model->load($map_id);
                if (empty($map)) throw new Exception ('Map not found!');
                $bean = $this->mapserver_model->createMapfile($map);
                $bean->fontset = './mapfile/fonts/fonts.list';
                $bean->symbolset = './mapfile/symbols/symbols.txt';
            }
            // Load map
            else {
                $bean = $this->mapserver_model->loadMapfile($id);
                if (!$bean) throw new Exception('MapServer map not found!');
            }
            
            // Load Mapserver Units
            $msunits = $this->mapserver_model->loadUnitsAll();
            
            // Load Mapserver Units
            $msmetadata = $this->mapserver_model->loadMetadataAll();
            
            // Load Mapserver layers
            $mslayers = $this->mapserver_model->loadLayerAll();
            
            // Correct projection params for textarea
            $proj_params = explode(" ", $bean->projection);
            foreach ($proj_params as &$param) $param = trim($param);
            $bean->projection = implode("\n", $proj_params);
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'mslegendctrlpath' => $this->mslegendctrlpath,
                'mslayerctrlpath' => $this->mslayerctrlpath,
                'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
                'msmapfile' => $bean,
                'msunits' => $msunits,
                'msmetadata' => $msmetadata,
                'mslayers' => $mslayers);
            if ($id === 'new') $data['action'] = '/save/new';
            else $data['action'] = '/save/'.$bean->id;
            $content = $this->load->view('admin/mapserver/admineditmap', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of mapfile
     * @param string $id 
     */
    public function save($id)
    {   
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new mapfile
            if ($id === 'new') {
                $map = $this->map_model->load($post['map_id']);
                if (empty($map)) throw new Exception('Map not found!');
                $msmapfile = $this->mapserver_model->createMapfile($map);
                // Load Metadata enable_ows_request Item
                $msmetadata = $this->mapserver_model->loadMetadata(9);
                $this->mapserver_model->addMapfileMetadata($msmapfile, $msmetadata, '*');
                $account = $this->account_model->load($this->session->userdata('username'));
                $msmapfile->owner = $account;
            }
            // Load existing style
            else {
                $msmapfile = $this->mapserver_model->loadMapfile($id);
                if (!$msmapfile) throw new Exception('MapServer map not found!');
            }

            // TODO: Validate data

            $fields = array(
                'extent',
                'projection',
                'sizex',
                'sizey',
                'debug',
                'fontset',
                'symbolset',
                'imagecolor',
                'imagetype'
            );
            
            // Correct projection params for input
            $proj_params = explode("\n", $post['projection']);
            foreach ($proj_params as &$param) $param = trim($param);
            $post['projection'] = implode(" ", $proj_params);
            
            // Set new data and save
            $msmapfile->import($post, implode(',', $fields));
            $msmapfile->msunits = $this->mapserver_model->loadUnits($post['msunits_id']);
            $this->mapserver_model->save($msmapfile);
            $info[] = 'The map was saved';
            $this->mapserver_model->updateMapfile($id);
            
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load Mapserver Units
        $msunits = $this->mapserver_model->loadUnitsAll();

        // Load Mapserver Units
        $msmetadata = $this->mapserver_model->loadMetadataAll();

        // Load Mapserver layers
        $mslayers = $this->mapserver_model->loadLayerAll();

        // Correct projection params for textarea
        $proj_params = explode(" ", $msmapfile->projection);
        foreach ($proj_params as &$param) $param = trim($param);
        $msmapfile->projection = implode("\n", $proj_params);

        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'mslegendctrlpath' => $this->mslegendctrlpath,
            'mslayerctrlpath' => $this->mslayerctrlpath,
            'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
            'msmapfile' => $msmapfile,
            'msunits' => $msunits,
            'msmetadata' => $msmetadata,
            'mslayers' => $mslayers);
        $data['action'] = ($id === 'new') ? '/save/new' : '/save/'.$msmapfile->id;
        $content = $this->load->view('admin/mapserver/admineditmap', $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action delete
     * Deleted the selected mapfile
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->mapserver_model->deleteMapfile($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath);
    }
    
    /**
     * Action savemetadata
     * Saves the new data to the ampfile metadata item
     * @param mixed $id
     * @param integer $msmapfile_id 
     */
    public function savemetadata($id, $msmapfile_id = null)
    {   
        try {
            // Load post data
            $value = $this->input->post('value');
            $msmetadata_id = $this->input->post('msmetadata_id');
            
            // Load Metadata Item
            $msmetadata = $this->mapserver_model->loadMetadata($msmetadata_id);
            
            // Save mapfile metadata item
            if ($id === 'new') {
                // Load mapfile
                $msmapfile = $this->mapserver_model->loadMapfile($msmapfile_id);
                if (!$msmapfile) throw new Exception('Mapfile not found!');
                $this->mapserver_model->addMapfileMetadata($msmapfile, $msmetadata, $value);
            }
            else {
                $msmapfilemd = $this->mapserver_model->addLoadMapfileMetadata($id);
                $msmapfile->value = $value;
                $this->mapserver_model->save($mslayermd);
            }
            $this->mapserver_model->updateMapfile($msmapfile_id);
        }
        catch (Exception $e) {
            //
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$msmapfile->id);
    }
    
    /**
     * Action delete mapfile metadata
     */
    public function delmetadata($msmapfile_id)
    {
        $selected = $this->input->post('selected');
        $this->mapserver_model->deleteMapfileMetadata($selected);
        $this->mapserver_model->updateMapfile($msmapfile_id);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$msmapfile_id);
    }
    
    /**
     * Action addlayer
     * Adds selected layers to the map
     */
    public function addlayer($msmapfile_id)
    {
        $msmapfile = $this->mapserver_model->loadMapfile($msmapfile_id);
        if (empty($msmapfile)) return;
        $selected = $this->input->post('selected');
        foreach ($selected as $mslayer_id) {
            $mslayer = $this->mapserver_model->loadLayer($mslayer_id);
            if (empty($mslayer)) continue;
            $this->mapserver_model->addMapfileLayer($msmapfile, $mslayer);
        }
        $this->mapserver_model->updateMapfile($msmapfile_id);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$msmapfile_id);
    }
    
    /**
     * Action dellayer
     * Removes selected layers from mapfile
     */
    public function dellayer($msmapfile_id)
    {
        $msmapfile = $this->mapserver_model->loadMapfile($msmapfile_id);
        if (empty($msmapfile)) return;
        $selected = $this->input->post('selected');
        $this->mapserver_model->delMapfileLayer($msmapfile, $selected);
        $this->mapserver_model->updateMapfile($msmapfile_id);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$msmapfile_id);
    }
    
    /**
     * Action updateMapfile
     * Updates the static map file
     * @param integer $id 
     */
    public function updatemapfile($id) {
        $this->mapserver_model->updateMapfile($id);
        
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$id);
        
    }
    
}