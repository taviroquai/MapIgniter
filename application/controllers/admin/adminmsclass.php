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

class Adminmsclass extends MY_Controller {

    protected $mslayerctrlpath;
    protected $mslabelctrlpath;
    protected $msstylectrlpath;
    protected $dataexplorerctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->mslayerctrlpath = 'admin/adminmslayer';
        $this->mslabelctrlpath = 'admin/adminmslabel';
        $this->msstylectrlpath = 'admin/adminmsstyle';
        $this->dataexplorerctrlpath = 'admin/dataexplorer';
    }
    
    /**
     * Action index
     * Display a list of mapserver classes
     */
    public function index($mslayer_id = null)
    {
        // Required mapserver layer
        if (empty($mslayer_id)) return;
        
        // Load mapserver layer
        $mslayer = $this->mapserver_model->loadLayer($mslayer_id);
        
        // Load all classes
        // TODO: Pagination
        $items = $this->mapserver_model->loadClassAll();
        
        // Load main content
        $data = array(
            'ctrlpath' => $this->ctrlpath,
            'items' => $items,
            'mslayer' => $mslayer,
            'action' => '/save/new');
        
        $content = $this->load->view('admin/mapserver/adminlayer', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a class
     * @param string $id 
     */
    public function edit($id, $mslayer_id = null)
    {   
        try {
            
            // Create new layer class
            if ($id === 'new') {
                // Load Mapserver Layer
                $mslayer = $this->mapserver_model->loadLayer($mslayer_id);
                if (!$mslayer) throw new Exception('MapServer layer not found!');
                $msclass = $this->mapserver_model->createClass($mslayer, 'new');
                $account = $this->account_model->load($this->session->userdata('username'));
                $msclass->owner = $account;
            }
            // Load existing class
            else {
                $msclass = $this->mapserver_model->loadClass($id);
                if (!$msclass) throw new Exception('Class not found!');
            }
            
            // Load all styles
            $msstyles = $this->mapserver_model->loadStyleAll();
            
            // Load all labels
            $mslabels = $this->mapserver_model->loadLabelAll();
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'mslayerctrlpath' => $this->mslayerctrlpath,
                'mslabelctrlpath' => $this->mslabelctrlpath,
                'msstylectrlpath' => $this->msstylectrlpath,
                'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
                'msclass' => $msclass, 
                'msstyles' => $msstyles,
                'mslabels' => $mslabels
                );
            
            // Set action
            if ($id === 'new') $data['action'] = '/save/new/'.$mslayer->id;
            else $data['action'] = '/save/'.$msclass->id;
            
            $content = $this->load->view('admin/mapserver/admineditclass', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action saveclass
     * Saves the new data of layer class
     * @param mixed $id
     */
    public function save($id, $mslayer_id = null)
    {   
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new layer class
            if ($id === 'new') {
                // Load Mapserver Layer
                $mslayer = $this->mapserver_model->loadLayer($mslayer_id);
                if (!$mslayer) throw new Exception('MapServer layer not found!');
                $msclass = $this->mapserver_model->createClass($mslayer, 'new');
                $account = $this->account_model->load($this->session->userdata('username'));
                $msclass->owner = $account;
            }
            // Load existing class
            else {
                $msclass = $this->mapserver_model->loadClass($id);
                if (!$msclass) throw new Exception('Class not found!');
            }

            // TODO: Validate data

            $fields = array(
                'name',
                'status',
                'expression',
                'text',
                'color',
                'bgcolor',
                'outlinecolor',
                'maxscaledenom',
                'minscaledenom',
                'symbol',
                'size'
            );
            
            $msclass->import($post, implode(',', $fields));
            $this->mapserver_model->save($msclass);
            $info[] = 'The class was saved';
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load all styles
        $msstyles = $this->mapserver_model->loadStyleAll();

        // Load all labels
        $mslabels = $this->mapserver_model->loadLabelAll();

        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'mslayerctrlpath' => $this->mslayerctrlpath,
            'mslabelctrlpath' => $this->mslabelctrlpath,
            'msstylectrlpath' => $this->msstylectrlpath,
            'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
            'msclass' => $msclass, 
            'msstyles' => $msstyles,
            'mslabels' => $mslabels,
            'action' => '/save/'.$msclass->id
            );

        $content = $this->load->view('admin/mapserver/admineditclass', $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action adlabel
     * Sets the label for the class
     * @param integer $msclass_id
     */
    public function addlabel($msclass_id) {
        $mslabel_id = $this->input->post('mslabel_id', TRUE);
        $msclass = $this->mapserver_model->loadClass($msclass_id);
        $mslabel = $this->mapserver_model->loadLabel($mslabel_id);
        if (!empty($msclass) && !empty($mslabel))
            $this->mapserver_model->addClassLabel($msclass, $mslabel);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$msclass_id);
    }
    
    /**
     * Action addstyle
     * Adds a style for the class
     * @param integer $msclass_id 
     */
    public function addstyle($msclass_id) {
        $msstyle_id = $this->input->post('msstyle_id', TRUE);
        $msclass = $this->mapserver_model->loadClass($msclass_id);
        $msstyle = $this->mapserver_model->loadStyle($msstyle_id);
        if (!empty($msclass) && !empty($msstyle))
            $this->mapserver_model->addClassLabel($msclass, $msstyle);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$msclass_id);
    }
    
    /**
     * Action delete class
     */
    public function delete($mslayer_id)
    {
        $selected = $this->input->post('selected');
        $this->mapserver_model->deleteClass($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->mslayerctrlpath.'/edit/'.$mslayer_id);
    }
    
    /**
     * Action delete label
     * Remove associated label
     */
    public function dellabel($msclass_id)
    {
        $selected = $this->input->post('selected');
        $msclass = $this->mapserver_model->loadClass($msclass_id);
        if (empty($msclass)) return;
        $this->mapserver_model->deleteClassLabel($msclass);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$msclass_id);
    }
    
    /**
     * Action delete style
     * Remove associated styles
     */
    public function delstyle($msclass_id)
    {
        $selected = $this->input->post('selected');
        $msclass = $this->mapserver_model->loadClass($msclass_id);
        if (empty($msclass)) return;
        $this->mapserver_model->deleteClassStyle($msclass, $selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'/edit/'.$msclass_id);
    }
    
}