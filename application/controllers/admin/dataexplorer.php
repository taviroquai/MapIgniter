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

class Dataexplorer extends MY_Controller {

    protected $mainview;
    protected $ajaxmainview;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('admin/dataexplorer_model');
        $this->layout = 'module';
        $this->mainview = 'admin/dataexplorer';
        $this->ajaxmainview = 'admin/ajaxdataexplorer';
        $this->ctrlpath = 'admin/dataexplorer';
    }
    
    public function index()
    {
        
        // load helpers, libraries
        $this->load->helper(array('form'));
        $this->cleanSelected();
        
        // Check for a return element, save on session (bad?)
        if ($this->input->get('return')) {
            $this->session->set_userdata('return', $this->input->get('return'));
            $this->session->unset_userdata('CKEditor');
        }
        // Check for a CKEditor parameters
        elseif ($this->input->get('CKEditor')) {
            $this->session->set_userdata('CKEditor', $this->input->get('CKEditor'));
            $this->session->set_userdata('CKEditorFuncNum', $this->input->get('CKEditorFuncNum'));
            $this->session->unset_userdata('return');
        }
        
        // Set public base directory
        if (!$this->session->userdata('data_security')) $this->session->set_userdata('data_security', 'private');
        if ($this->input->get('security')) {
            $this->session->set_userdata('data_security', $this->input->get('security'));
        }
        $security = $this->session->userdata('data_security');
        
        // Get upper directory
        $back = $this->input->get('back');
        if (empty($back)) $back = './';
        
        // Get requested list
        $list = $this->input->get('list');
        if (empty($list)) $list = './';
        $dir = $list;
        $security = $this->findSecurity($list, $security);
        
        // Get directory list
        try {
            $list = $this->dataexplorer_model->listdir($list, $security);
        
            $data['ctrlpath'] = $this->ctrlpath;
            $data['back'] = $back;
            $data['dir'] = $dir;
            $data['security'] = $security;
            $data['base'] = $this->dataexplorer_model->getBase($security);
            $data['list'] = $list;
            $data['selected'] = $this->session->userdata('data_selected');
            $data['return'] = $this->session->userdata('return');
            $data['replace'] = $this->input->get('replace');
            $data['CKEditor'] = $this->session->userdata('CKEditor');
            $data['CKEditorFuncNum'] = $this->session->userdata('CKEditorFuncNum');
            
            /*
             * Consider several calls to this controller and choose view
             */
            if (!empty($data['CKEditor'])) {
                $view = $this->ajaxmainview;
            }
            elseif ($this->input->is_ajax_request()) {
                $view = $this->ajaxmainview;
            }
            else $view = $this->mainview;
            $content = $this->load->view($view, $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<h2>File explorer</h2>";
            $content .= "<p>{$e->getMessage()}</p>";
            $content .= '<p>Click <a href="'.base_url().'admin/dataexplorer">here</a> to go back.</p>';
        }
        
        // Load layout
        $this->render($content);

    }
    
    public function dl() {
        $dir = $this->input->get('dir');
        $file = $this->input->get('file');
        $security = $this->session->userdata('data_security');
        $this->dataexplorer_model->dl($dir, $file, $security);
    }
    
    public function createdir() {
        $name = $this->input->post('name', TRUE);
        $security = $this->session->userdata('data_security');
        $base = $this->dataexplorer_model->getBase($security);
        $account = $this->account_model->load($this->session->userdata('username'));
        $result = $this->dataexplorer_model->createdir($base, $name, $security, $account, 0755);
        $dir = $this->input->get('dir');
        
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath.'?list='.$dir);
    }
    
    public function deldir() {
        $dir = $this->input->get('dir');
        $list = $this->input->get('list');
        $security = $this->session->userdata('data_security');
        $base = $this->dataexplorer_model->getBase($security);
        $this->dataexplorer_model->deldir($base, $dir, $security);
        
        if (!$this->input->is_ajax_request()) 
            redirect(base_url().$this->ctrlpath.'?list='.$list);
    }
    
    public function upload() {
        $dir = $this->input->post('dir');
        $overwrite = $this->input->post('overwrite');
        $security = $this->session->userdata('data_security');
        $base = $this->dataexplorer_model->getBase($security);

        try {
            $account = $this->account_model->load($this->session->userdata('username'));
            $data = $this->dataexplorer_model->uploadfile($base, $dir, $overwrite, $security, $account);
                        
            // Get upper directory
            $back = $this->input->get('back');
            if (empty($back)) $back = './';

            // Get requested list
            $list = $this->input->get('list');
            if (empty($list)) $list = './';
            $dir = $list;
            $security = $this->findSecurity($list, $security);
            
            $list = $this->dataexplorer_model->listdir($list, $security);
            $data['ctrlpath'] = $this->ctrlpath;
            $data['back'] = $back;
            $data['dir'] = $dir;
            $data['security'] = $security;
            $data['base'] = $this->dataexplorer_model->getBase($security);
            $data['list'] = $list;
            $data['selected'] = $this->session->userdata('data_selected');
            $data['return'] = $this->session->userdata('return');
            $data['replace'] = $this->input->get('replace');
            $data['CKEditor'] = $this->session->userdata('CKEditor');
            $data['CKEditorFuncNum'] = $this->session->userdata('CKEditorFuncNum');
            
            /*
             * Consider several calls to this controller and choose view
             */
            if (!empty($data['CKEditor'])) {
                $view = $this->ajaxmainview;
                $this->layout = 'module';
            }
            elseif ($this->input->is_ajax_request()) {
                $view = $this->ajaxmainview;
            }
            else $view = $this->mainview;
            
            $content = $this->load->view($view, $data, TRUE);

        }
        catch (Exception $e) {
            $content = "<h2>File explorer</h2>";
            $content .= "<p>{$e->getMessage()}</p>";
            $content .= '<p>Click <a href="'.base_url().'admin/dataexplorer">here</a> to go back.</p>';
        }
        
        // Load layout
        $this->render($content);
    }
    
    public function delfile() {
        $file = $this->input->get('file');
        $list = $this->input->get('list');
        $security = $this->session->userdata('data_security');
        $base = $this->dataexplorer_model->getBase($security);
        $this->dataexplorer_model->delfile($base, $file, $security);
        
        if (!$this->input->is_ajax_request()) 
            redirect(base_url().$this->ctrlpath.'?list='.$list);
    }
    
    public function selected()
    {
        $dir = $this->input->get('list');
        $selected = $this->input->post('selected');
        $action = $this->input->post('action');
        $security = $this->session->userdata('data_security');
        
        switch($action) {
            case 'delete':
                if (!empty($selected)) {
                    $base = $this->dataexplorer_model->getBase($security);
                    foreach($selected as $item) {
                        if (is_dir($base.$item)) $this->dataexplorer_model->deldir($base, $item, $security);
                        if (is_file($base.$item)) $this->dataexplorer_model->delfile($base, $item, $security);
                    }
                    $this->cleanSelected();
                }
                break;
            case 'unselect':
                if (!empty($selected)) {
                    $selection = $this->session->userdata('data_selected');
                    $new_selection = array();
                    foreach ($selection as &$item) {
                        if (!in_array($item, $selected)) $new_selection[] = $item;
                    }
                    $this->session->set_userdata('data_selected', $new_selection);
                }
                if (!$this->input->is_ajax_request()) 
                    redirect(base_url().$this->ctrlpath.'?list='.$dir.'#selected');
                break;
            default:
                $selection = $this->session->userdata('data_selected');
                if (empty($selection)) $selection = array();
                if (!empty($selected)) {
                    foreach ($selected as &$item) {
                        if (!in_array($item, $selection)) $selection[] = $item;
                    }
                }
                $this->session->set_userdata('data_selected', $selection);
        }
        if (!$this->input->is_ajax_request()) 
            redirect(base_url().$this->ctrlpath.'?list='.$dir);
        
    }
    
    private function cleanSelected() {
        // Clean non-existing selected files
        $selected = $this->session->userdata('data_selected');
        if (!empty($selected)) {
            $security = $this->session->userdata('data_security');
            $base = $this->dataexplorer_model->getBase($security);
            $i = 0;
            while($i<count($selected)) {
                if (!file_exists($base.$selected[$i])) unset($selected[$i]);
                $i++;
            }
        }
        else $selected = array();
        $this->session->set_userdata('data_selected', $selected);
        return $selected;
    }
    
    private function findSecurity($list, $security) {
        $dir = $list;
        if (!$this->dataexplorer_model->dirExists($dir, $security)) {
            if ($security === 'private') $tsecurity = 'public';
            else $tsecurity = 'private';
            if ($this->dataexplorer_model->dirExists($dir, $tsecurity)) {
                $security = $tsecurity;
            }
            $this->session->set_userdata('data_security', $security);
        }
        return $security;
    }
    
}