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

class Adminlayouts extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        // Set layout
        $this->layout = 'admin';
        $this->load->model('layout/layout_model');
    }
    
    /**
     * Action index
     * Display a list of Layouts
     * TODO: Pagination
     */
    public function index()
    {   
        // Load All Layouts
        // TODO: Pagination
        $beans = $this->layout_model->loadAll();
        
        // Load main content
        $content = $this->load->view('admin/layouts/adminlayouts', array('items' => $beans), TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for layout edition
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load layout
            $layout = $this->layout_model->loadById($id);
            if (!$layout) throw new Exception('Layout not found!');
            
            // Create a slot
            $slot = $this->layout_model->createSlot();
            
            // Load all blocks
            $modules = $this->layout_model->loadModuleAll();
            $slots = $layout->ownLslot;
            foreach ($slots as &$slot) {
                $blocks = $slot->sharedLblock;
                foreach ($blocks as &$block) {
                    $block->editpath = $this->getBlockEditControllerPath ($block).'/edit/';
                }
            }
            
            // Load main content
            $data = array(
                'layout'    => $layout, 
                'slot'      => $slot,
                'modules'   => $modules);
            $content = $this->load->view('admin/layouts/admineditlayout', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action editslot
     * Opens a form for layout slot edition
     * @param integer $layout_id
     * @param integer $id 
     */
    public function editslot($layout_id, $id)
    {   
        try {
            // Load layout
            $layout = $this->layout_model->loadById($layout_id);
            
            // Load layout slot
            $slot = $this->layout_model->loadSlot($id);
            if (!$slot) throw new Exception('Layout slot not found!');
            
            // Load main content
            $data = array('layout' => $layout, 'slot' => $slot);
            $content = $this->load->view('admin/layouts/admineditslot', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);

    }
    
    /**
     * Action save
     * Saves the new data of the layout
     * @param string $id 
     */
    public function save($id)
    {   
        try {
            // load post data
            $name = $this->input->post('name');
            $view = $this->input->post('view');
            $content = $this->input->post('content');
            
            // Create new layout
            if ($id === 'new') {
                $layout = $this->layout_model->create();
                $account = $this->account_model->load($this->session->userdata('username'));
                $layout->owner = $account;
            }
            // Load existing menu
            else {
                $layout = $this->layout_model->loadById($id);
                if (!$layout) throw new Exception('Layout not found!');
            }

            // Set new data and save
            if (!empty($name) && !empty($view)) {
                $layout->name = $name;
                $layout->view = $view;
                $layout->content = $content;
                $this->layout_model->save($layout);    
            }
        }
        catch(Exception $e) {
            //
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminlayouts/edit/'.$layout->id);
    }
    
    /**
     * Action saveslot
     * Saves the new data of the layout slot
     * @param integer $layout_id
     * @param string $id 
     */
    public function saveslot($layout_id, $id)
    {   
        try {
            // Load layout
            $layout = $this->layout_model->loadById($layout_id);
            
            // Load post data
            $name = $this->input->post('name');

            // Create layout slot
            if ($id === 'new') {
                // Load existing menu
                $layout_id = $this->input->post('layout_id');
                $layout = $this->layout_model->loadById($layout_id);
                if (!$layout) throw new Exception('Layout not found!');
                $slot = $this->layout_model->createSlot($name, $layout);
                $account = $this->account_model->load($this->session->userdata('username'));
                $slot->owner = $account;
            }
            // Load existing menuitem
            else {
                $slot = $this->layout_model->loadSlot($id);
                if (!$slot) throw new Exception('Layout slot not found!');
            }
            
            // Set new data and save
            if (!empty($name)) {
                $slot->name = $name;
                $this->layout_model->save($slot);
            }
        }
        catch (Exception $e) {
            //
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminlayouts/editslot/'.$layout->id.'/'.$slot->id);
    }
    
    /**
     * Create a new block
     * @param integer $layout_id
     */
    public function createblock($layout_id)
    {   
        try {
            // Load layout
            $layout = $this->layout_model->loadById($layout_id);
            
            // Load post data
            $name = $this->input->post('name');
            $config = $this->input->post('config');
            $module_id = $this->input->post('module_id');
            $module_item = $this->input->post('module_item');
            $slot_id = $this->input->post('slot_id');
            $publish = $this->input->post('publish');
            $publish_order = $this->input->post('publish_order');
            
            // Create layout block
            $slot = $this->layout_model->loadSlot($slot_id);
            if (!$slot) throw new Exception('Slot not found!');
            $module = $this->layout_model->loadModule($module_id);
            if (!$module) throw new Exception('Module not found!');
            $lblock = $this->layout_model->createBlock($name, $module, 1, $config);
            $lblock->publish = 0;
            $account = $this->account_model->load($this->session->userdata('username'));
            $lblock->owner = $account;
            $this->layout_model->slotAddBlock($slot, $lblock);
            
            // validate input
            if (empty($name)) throw new Exception('Name cannot be null');
            if (empty($module)) throw new Exception('Module not found');

            // Set new data and save
            $lblock->name = $name;
            $lblock->module = $module;
            $lblock->config = $config;
            $lblock->item = $module_item;
            $lblock->publish = (int) $publish;
            $lblock->publish_order = (int) $publish_order;
            $this->layout_model->save($lblock);
            
            // Redirect to specific module configuration controller
            $editpath = $this->getBlockEditControllerPath ($lblock);
            if (!$this->input->is_ajax_request())
                redirect(base_url($$editpath.'/edit/'.$layout->id.'/'.$lblock->id));
        }
        catch (Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        redirect(base_url('admin/adminlayouts/edit/'.$layout_id));
    }
    
    /**
     * Action delete
     * Deleted the selected layouts
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->layout_model->delete($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminlayouts');
    }
    
    /**
     * Action deleteslot
     * Deletes the selected layout slots
     */
    public function deleteslot()
    {   
        $layout_id = $this->input->post('layout_id');
        $selected = $this->input->post('selected');
        $this->layout_model->deleteSlot($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminlayouts/edit/'.$layout_id.'#editslots');
    }
    
    /**
     * Action deleteblock
     * Deletes the selected slot blocks
     */
    public function deleteblock()
    {
        $layout_id = $this->input->post('layout_id');
        $selected = $this->input->post('selected');
        $this->layout_model->deleteLblock($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminlayouts/edit/'.$layout_id.'#editblocks');
    }
    
    /**
     * Resolves block edit controller
     * @param Lblock $block
     * @return string
     */
    private function getBlockEditControllerPath($block) {
        return 'block/'.reset(explode('_', end(explode('/', $block->module->path))));
    }
    
}