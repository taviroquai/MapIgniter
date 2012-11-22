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

class Adminmsstyle extends MY_Controller {

    protected $dataexplorerctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->dataexplorerctrlpath = 'admin/dataexplorer';
    }
    
    /**
     * Action index
     * Display a list of user defined styles
     */
    public function index()
    {   
        // Load all user styles
        // TODO: Pagination
        $items = $this->mapserver_model->loadStyleAll();
        
        // Temp style
        $msstyle = $this->mapserver_model->createStyle();
        
        // Load main content
        $data = array(
            'ctrlpath' => $this->ctrlpath,
            'items' => $items,
            'msstyle' => $msstyle,
            'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
            'action' => '/save/new');
        $content = $this->load->view('admin/mapserver/adminstyle', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a style
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load style
            $bean = $this->mapserver_model->loadStyle($id);
            if (!$bean) throw new Exception('MapServer style not found!');
            
            // Load data explorer model
            $this->load->model('admin/dataexplorer_model');
            if ($this->dataexplorer_model->fileExists($bean->symbol)) {
                $preview = base_url().'user/userdataexplorer/dl?dir=./&file='.$bean->symbol;
            }
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
                'msstyle' => $bean,
                'action' => '/save/'.$bean->id);
            if (!empty($preview)) $data['sym_preview'] = $preview;
            $content = $this->load->view('admin/mapserver/admineditstyle', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of user style
     * @param string $id 
     */
    public function save($id)
    {
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new mapserver style
            if ($id === 'new') {
                $msstyle = $this->mapserver_model->createStyle('new');
                $account = $this->account_model->load($this->session->userdata('username'));
                $msstyle->owner = $account;
            }
            // Load existing style
            else {
                $msstyle = $this->mapserver_model->loadStyle($id);
                if (!$msstyle) throw new Exception('MapServer style not found!');
            }

            // Validate data and save
            if (empty($post['description'])) throw new Exception ('Invalid description');

            $fields = array(
                'description',
                'angle',
                'antialias',
                'bgcolor',
                'color',
                'gap',
                'geomtransform',
                'linecap',
                'linejoin',
                'linejoinmaxsize',
                'maxsize',
                'maxwidth',
                'offset',
                'opacity',
                'outlinecolor',
                'pattern',
                'size',
                'symbol',
                'width'
            );
            $msstyle->import($post, implode(',', $fields));
            $this->mapserver_model->save($msstyle);
            $info[] = 'O estilo foi guardado.';
            
            if ($msclass_id = $this->input->get('msclass', TRUE)) {
                $msclass = $this->mapserver_model->loadClass($msclass_id);
                if (!empty($msclass)) {
                    $this->mapserver_model->addClassStyle($msclass, $msstyle);
                    $info[] = 'The style was added to class';
                }
            }
            
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load data explorer model
        $this->load->model('admin/dataexplorer_model');
        if ($this->dataexplorer_model->fileExists($msstyle->symbol)) {
            $preview = base_url().'user/userdataexplorer/dl?dir=./&file='.$msstyle->symbol;
        }
        
        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'dataexplorerctrlpath' => $this->dataexplorerctrlpath,
            'msstyle' => $msstyle,
            'action' => '/save/'.$msstyle->id);
        if (!empty($preview)) $data['sym_preview'] = $preview;
        $content = $this->load->view('admin/mapserver/admineditstyle', $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action delete
     * Deleted the selected mapserver style
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->mapserver_model->deleteStyle($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath);
    }
    
}