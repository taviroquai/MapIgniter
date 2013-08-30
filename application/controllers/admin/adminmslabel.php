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

class Adminmslabel extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        
    }
    
    /**
     * Action index
     * Display a list of registered mapserver labels
     */
    public function index()
    {   
        // Load all mapserver labels
        // TODO: Pagination
        $items = $this->mapserver_model->loadLabelAll();
        
        // Temp label
        $mslabel = $this->mapserver_model->createLabel();
        
        // Load main content
        $data = array(
                    'ctrlpath' => $this->ctrlpath,
                    'items' => $items,
                    'mslabel' => $mslabel,
                    'action' => '/save/new');
        $content = $this->load->view('admin/mapserver/adminlabel', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a mapserver label
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load label
            if ($id === 'new') {
                $bean = $this->mapserver_model->createLabel();
                $form_action = '/save/new';
                if ($msclass_id = $this->input->get('msclass', TRUE)) {
                    $form_action .= '?msclass='.$msclass_id;
                }
            }
            else {
                $bean = $this->mapserver_model->loadLabel($id);
                if (!$bean) throw new Exception('Label not found!');
                $form_action = '/save/'.$bean->id;
            }
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'mslabel' => $bean,
                'action' => $form_action);
            $content = $this->load->view('admin/mapserver/admineditlabel', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of mapserver label
     * @param string $id 
     */
    public function save($id)
    {   
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new mapserver label
            if ($id === 'new') {
                $mslabel = $this->mapserver_model->createLabel();
                $account = $this->account_model->load($this->session->userdata('username'));
                $mslabel->owner = $account;
            }
            // Load existing label
            else {
                $mslabel = $this->mapserver_model->loadLabel($id);
                if (!$mslabel) throw new Exception('Label not found!');
            }

            // Validate data
            // TODO: Complete validation
            if (empty($post['description'])) throw new Exception ('Invalid description');
            
            // Save new data
            $fields = array(
                'description',
                'font',
                'encoding',
                'type',
                'size',
                'minsize',
                'maxsize',
                'outlinewidth',
                'buffer',
                'shadowsize',
                'maxlength',
                'mindistance',
                'minfeaturesize',
                'offset',
                'wrap',
                'color',
                'bgcolor',
                'outlinecolor',
                'shadowcolor',
                'align',
                'position',
                'angle',
                'antialias',
                'force',
                'maxoverlapangle',
                'partials',
                'priority',
                'repeatdistance'
            );
            $mslabel->import($post, implode(',', $fields));
            $this->mapserver_model->save($mslabel);
            $info[] = 'A etiqueta foi guardada';
                
            if ($msclass_id = $this->input->get('msclass', TRUE)) {
                $msclass = $this->mapserver_model->loadClass($msclass_id);
                if (!empty($msclass))
                    $this->mapserver_model->addClassLabel($msclass, $mslabel);
            }
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load main content
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ctrlpath' => $this->ctrlpath,
            'mslabel' => $mslabel,
            'action' => '/save/'.$mslabel->id);
        $content = $this->load->view('admin/mapserver/admineditlabel', $data, TRUE);
        $this->render($content);
        
    }
    
    /**
     * Action delete
     * Deleted the selected mapserver label
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->mapserver_model->deleteLabel($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath);
    }
    
}