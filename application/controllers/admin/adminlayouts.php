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
     * Action editblock
     * Opens a form for block edition
     * @param integer $layout_id
     * @param integer $id 
     */
    public function editblock($layout_id, $id)
    {   
        try {
            // Load layout
            $layout = $this->layout_model->loadById($layout_id);
            
            // Load layout slot
            $block = $this->layout_model->loadBlock($id);
            if (!$block) throw new Exception('Layout slot not found!');
            
            // Load module items
            $module_items = null;
            if ($block->module->table) {
                $this->load->model('database/database_model');
                $module_items = $this->database_model->find($block->module->table, ' true ');
            }
            
            // Load main content
            $data = array(
                'layout'    => $layout,
                'block'     => $block, 
                'module_items' => $module_items);
            $content = $this->load->view('admin/layouts/admineditblock', $data, TRUE);
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
     * Save block
     * @param integer $layout_id
     * @param integer $id 
     */
    public function saveblock($layout_id, $id)
    {   
        try {
            // Load layout
            $layout = $this->layout_model->loadById($layout_id);
            
            // Load post data
            $name = $this->input->post('name');
            $config = $this->input->post('config');
            $module_id = $this->input->post('module_id');
            $module_item = $this->input->post('module_item');
            
            // Create layout block
            if ($id === 'new') {
                $slot_id = $this->input->post('slot_id');
                $slot = $this->layout_model->loadSlot($slot_id);
                if (!$slot) throw new Exception('Slot not found!');
                $module = $this->layout_model->loadModule($module_id);
                if (!$module) throw new Exception('Module not found!');
                $lblock = $this->layout_model->createBlock($name, $module, $config);
                $account = $this->account_model->load($this->session->userdata('username'));
                $lblock->owner = $account;
                $this->layout_model->slotAddBlock($slot, $lblock);
            }
            else {
                $lblock = $this->layout_model->loadBlock($id);
                if (!$lblock) throw new Exception('Block not found!');
                $module = $this->layout_model->loadModule($module_id);
                if (!$module) throw new Exception('Module not found!');
            }

            // Set new data and save
            if (!empty($name) && !empty($module)) {
                $lblock->name = $name;
                $lblock->module = $module;
                $lblock->config = $config;
                $lblock->item = $module_item;
                $this->layout_model->save($lblock);
            }
        }
        catch (Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminlayouts/editblock/'.$layout->id.'/'.$lblock->id);
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
     * Loads the layout and renders out
     * @param string $content
     * @return null
     */
    protected function render($content) {
        if ($this->input->is_ajax_request()) {
            echo $content;
            return;
        }
        // Load layout and render
        $this->loadLayout('admin', $content);
    }
    
}