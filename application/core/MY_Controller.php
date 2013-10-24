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

class MY_Controller extends CI_Controller {

    public $ctrlpath;
    public $layout;
    protected $account;
    
    public function __construct() {
        parent::__construct();
        
        // check for configuration
        $filename = APPPATH.'/config/mapigniter.php';
        if (!file_exists($filename)) redirect(base_url('install'));
        $filename = APPPATH.'/config/database.php';
        if (!file_exists($filename)) redirect(base_url('install'));
        
        // Load MapIgniter Configuration
        $this->load->config('mapigniter');
        
        /**
         * RedBeanPHP is loaded here, which leave other controller to do not
         * extend CI_Controller if they don't need access to database and check
         * permissions improving performance.
         */
        $this->load->library('rb');
        
        $lang = $this->session->userdata('lang');
        if (empty($lang)) $this->session->set_userdata('lang', 'english');
        
        $this->layout = 'public';
        $path = $this->router->fetch_class();
        $this->load->model('database/database_model');
        
        try {
            $result = $this->database_model->find('controller', 'path = ?', array($path));
        } catch (PDOException $e) {
            show_error($e->getMessage());
        }
        if (!empty($result)) {
            $ctl = reset($result);
            if (!empty($ctl->layout->name)) $this->layout = $ctl->layout->name;
        }
        
        $this->load->model('account/account_model');
        $this->load->model('account/group_model');
        
        $this->checkSchema();

        $username = $this->session->userdata('username');
        if (empty($username)) {
            $username = 'guest';
            $this->session->set_userdata('username', $username);
        }

        $this->account = $this->account_model->load($username);
        if (empty($this->account)) return;
        
        // Save visit if it is not ajax
        if (!$this->input->is_ajax_request()) {
            $ip = $this->input->ip_address();
            $visit = $this->account_model->findVisit($this->account, $ip);
            if (empty($visit)) {
                $datetime = date('Y-m-d H:i:s');
                $visit = $this->account_model->createVisit($this->account, $ip, $datetime);
                $this->database_model->save($visit);
            }
        }

        $allowed = $this->group_model->isAccountAllowed($this->account, $this->uri->uri_string());
        if (!$allowed) $this->accessDenied();
        
    }
    
    public function accessDenied() {
        $this->lang->load('general', $this->session->userdata('lang'));
        show_error($this->lang->line('error.403.message') , 403 );
        exit();
    }
    
    /**
     * Loads the layout and renders out
     * @param string $content
     * @return null
     */
    protected function render($content, $data = array(), $ajax = false) {
        if ($this->input->is_ajax_request() || $ajax) {
            echo $content;
            return;
        }
        // Load layout and render
        $this->loadLayout($this->layout, $content, $data);
    }
    
    protected function loadLayout($name, $main_block = null, $data = array()) {
        
        // Init variables
        $output = null;
        $slots = array('_links' => array(), '_scripts' => array());
        
        // Load layout model
        $this->load->model('layout/layout_model');
        
        // load configuration
        $config = $this->layout_model->load($name);
        if (empty($config)) {
            $config = $this->layout_model->load('public');
        }
        
        if (is_string($main_block)) {
            $content = $main_block;
        }
        elseif (is_subclass_of($main_block, 'lblock_model')) {
            
            // render main block
            $content = $main_block->render($data);
            
            // Add main block Links and Scripts to slots
            $this->addSlotItems($slots['_links'], $main_block->getLinks());
            $this->addSlotItems($slots['_scripts'], $main_block->getScripts());
            
        }
        
        $slots_config = $this->layout_model->getSlots($config);
        foreach($slots_config as $slot) {
            $blocks = $this->layout_model->getPublishedBlocks($slot);
            $slots[$slot->name] = '';
            if (!empty($blocks)) {
                foreach($blocks as $block) {
                    if (empty($block->module->path)) continue;
                    $blockpath = $block->module->path;
                    $modname = end(explode('/', $blockpath));
                    $this->load->model($blockpath, $block->name);
                    $lblock  = $this->{$block->name};
                    $lblock->addData('_instance', strtolower($block->name));
                    $lblock->addData('config', json_decode(strtolower($block->config), true));
                    $item = null;
                    if (!empty($block->item)) {
                        $item = $this->database_model->load($block->module->table, $block->item);
                        $lblock->addData('item', $item);
                    }
                    $slots[$slot->name] .= $lblock->render();

                    // Add lblock Links and Scripts to slots
                    $this->addSlotItems($slots['_links'], $lblock->getLinks());
                    $this->addSlotItems($slots['_scripts'], $lblock->getScripts());

                }
            }
        }

        $this->load->vars(array('_slot' => $slots));
        
        // Load layout
        $data['content'] = $content;
        $this->load->view($config['view'], $data);
    }
    
    protected function addSlotItems(&$slot, $items) {
        if (!empty($items)) {
            foreach ($items as $item) {
                if (!in_array($item, $slot)) {
                    $slot[] = $item;
                }
            }
        }
    }
    
    private function checkSchema() {
        try {
            $tables = $this->database_model->checkSchema();
            if (count($tables) < 35) throw new Exception ('Database is empty. Please install schema.');
        }
        catch (Exception $e) {
            if ($this->uri->uri_string() != 'admin/install') {
                redirect(base_url().'admin/install');
            }
        }
    }
    
}