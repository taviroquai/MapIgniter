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

class Adminblock extends MY_Controller {

    protected $viewedit;
    protected $editaction;
    protected $saveaction;
    
    public function __construct() {
        parent::__construct();
        
        // set default edit view
        $this->viewedit = 'admin/layouts/admineditblock';
        
        // Set layout
        $this->layout = 'admin';
        $this->load->model('layout/layout_model');
    }
    
    /**
     * Action edit
     * Opens a form for block edition
     * @param integer $layout_id
     * @param integer $id 
     */
    public function edit($layout_id, $id)
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
            
            // Dirty way to get block slot.
            $slots = $layout->ownLslot;
            foreach ($slots as $item) {
                $blocks = $item->sharedLblock;
                if (!empty($blocks)) :
                    foreach ($blocks as $tblock) {
                        if ($tblock->id == $block->id) $slot_id = $item->id;
                    }
                endif;
            }
            
            // Load main content
            $data = array(
                'saveaction'=> $this->saveaction,
                'layout'    => $layout,
                'block'     => $block, 
                'slots'     => $slots,
                'slot_id'   => $slot_id,
                'module_items' => $module_items);
            $this->beforeEdit($data);
            $content = $this->load->view($this->viewedit, $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);

    }
    
    /**
     * Save module block
     * @param integer $layout_id
     * @param integer $id 
     */
    public function save($layout_id, $id)
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
            $old_slot_id = $this->input->post('old_slot_id');
            $publish = $this->input->post('publish');
            $publish_order = $this->input->post('publish_order');
            
            // Check for slot change, create new block if true
            if (!empty($old_slot_id) && $old_slot_id != $slot_id) {
                $this->layout_model->deleteLblock(array($id));
                $id = 'new';
            }
            
            // Create layout block
            if ($id === 'new') {
                $slot = $this->layout_model->loadSlot($slot_id);
                if (!$slot) throw new Exception('Slot not found!');
                $module = $this->layout_model->loadModule($module_id);
                if (!$module) throw new Exception('Module not found!');
                $lblock = $this->layout_model->createBlock($name, $module, 1, $config);
                $lblock->publish = 0;
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
                $lblock->publish = (int) $publish;
                $lblock->publish_order = (int) $publish_order;
                $this->beforeSave($lblock);
                $this->layout_model->save($lblock);
            }
        }
        catch (Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        if (!$this->input->is_ajax_request()) {
            redirect(base_url($this->editaction.'/'.$layout->id.'/'.$lblock->id));
        }
    }
    
    /**
     * Hook to allow custom view data modifications
     * @param array $data
     */
    protected function beforeEdit(&$data) {
    }
    
    /**
     * Hook to allow custom operations before save
     * @param RedBean_OODBBean $block
     */
    protected function beforeSave(&$block) {
    }
    
}