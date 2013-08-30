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

class Adminmslegend extends MY_Controller {

    protected $msmapctrlpath;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('mapserver/mapserver_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->msmapctrlpath = 'admin/adminmsmap';
    }
    
    /**
     * Action index
     * Display a list of mapserver legends
     */
    public function index()
    {   
        // Load all legends
        // TODO: Pagination
        $items = $this->mapserver_model->loadLegendAll();
        
        // Load main content
        $data = array(
            'items' => $items,
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        $content = $this->load->view('admin/mapserver/adminlegend', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for edition of a legend
     * @param string $id 
     */
    public function edit($id, $msmapfile_id = null)
    {   
        try {
            
            // Create new mapserver legend
            if ($id === 'new') {
                $msmapfile = $this->mapserver_model->loadMapfile($msmapfile_id);
                if (empty($msmapfile)) throw new Exception ('MapServer map not found!');
                $bean = $this->mapserver_model->createLegend($msmapfile);
            }
            // Load legend
            else {
                $bean = $this->mapserver_model->loadLegend($id);
                if (!$bean) throw new Exception('Legend not found!');
            }
            
            // Load labels
            $mslabels = $this->mapserver_model->loadLabelAll();
            
            // Load main content
            $data = array(
                'ctrlpath' => $this->ctrlpath,
                'mslegend' => $bean,
                'mslabels' => $mslabels,
                'action' => '/save/'.$bean->id);
            $content = $this->load->view('admin/mapserver/admineditlegend', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new data of mapserver legend
     * @param string $id 
     */
    public function save($id)
    {   
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new mapserver legend
            if ($id === 'new') {
                $msmapfile = $this->mapserver_model->loadMapfile($post['msmapfile_id']);
                if (empty($msmapfile)) throw new Exception ('MapServer map not found!');
                $mslegend = $this->mapserver_model->createLegend($msmapfile);
                $account = $this->account_model->load($this->session->userdata('username'));
                $mslegend->owner = $account;
            }
            // Load legend
            else {
                $mslegend = $this->mapserver_model->loadLegend($id);
                if (!$mslegend) throw new Exception('Legend not found!');
            }

            // TODO: Validate data

            $fields = array(
                'imagecolor',
                'keysize',
                'keyspacing',
                'outlinecolor',
                'status',
                'position',
                'postlabelcache',
                'template'
            );

            $mslegend->import($post, implode(',', $fields));
            $mslegend->msmapfile = $this->mapserver_model->loadMapfile($post['msmapfile_id']);
            $mslegend->mslabel = $this->mapserver_model->loadLabel($post['mslabel_id']);
            $this->mapserver_model->save($mslegend);
            $info[] = 'A legenda foi guardada.';
            
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        
        // Load labels
        $mslabels = $this->mapserver_model->loadLabelAll();

        // Load main content
        $data = array(
            'ctrlpath' => $this->ctrlpath,
            'mslegend' => $mslegend,
            'mslabels' => $mslabels,
            'action' => '/save/'.$mslegend->id);
        $content = $this->load->view('admin/mapserver/admineditlegend', $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action delete
     * Deleted the selected legend
     */
    public function delete($msmapfile_id)
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->mapserver_model->deleteLegend($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->msmapctrlpath.'/edit/'.$msmapfile_id);
    }
    
}