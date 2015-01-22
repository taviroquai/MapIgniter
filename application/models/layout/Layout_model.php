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

class Layout_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
    }
    
    public function load($name)
    {
        $bean = $this->database_model->findOne('layout', ' name = ? ', array($name));
        return $bean;
    }
    
    public function loadById($id)
    {
        return $this->database_model->load('layout', $id);
    }
    
    public function loadAll() {
        return $this->database_model->find('layout', ' true ');
    }
    
    public function loadModule($id) {
        return $this->database_model->load('module', $id);
    }
    
    public function loadModuleAll() {
        return $this->database_model->find('module', ' true ');
    }
    
    public function loadSlot($id) {
        return $this->database_model->load('lslot', $id);
    }
    
    public function loadBlock($id) {
        return $this->database_model->load('lblock', $id);
    }
    
    public function create($name = 'new_layout', $view = '', $content = '<p>Content</p>')
    {
        $bean = $this->database_model->create('layout');
        $bean->name = $name;
        $bean->view = $view;
        $bean->content = $content;
        return $bean;
    }
    
    public function save(&$bean)
    {    
        return $this->database_model->save($bean);
    }
    
    public function createSlot($name = 'new_slot', &$layout = null)
    {
        $bean = $this->database_model->create('lslot');
        $bean->name = $name;
        if ($layout) {
            $bean->layout = $layout;
            $this->database_model->save($bean);
        }
        return $bean;
    }
    
    public function addSlot(&$layout, &$slot) {
        $layout->ownLslot[] = $slot;
        $this->database_model->save($slot);
    }
    
    public function createModule($name, $path, $table = '', $previewimg = '') {
        $bean = $this->database_model->create('module');
        $bean->name = $name;
        $bean->path = $path;
        $bean->table = $table;
        $bean->previewimg = $previewimg;
        return $bean;
    }
    
    public function createBlock($name, $module, $order = 1, $config = '[]', $item = null) {
        $bean = $this->database_model->create('lblock');
        $bean->name = $name;
        $bean->module = $module;
        $bean->item = $item;
        $bean->config = $config;
        $bean->publish = 1;
        $bean->publish_order = $order;
        return $bean;
    }
    
    public function slotAddBlock(&$slot, &$lblock) {
        $slot->sharedLblock[] = $lblock;
        $this->database_model->save($slot);
        $this->database_model->save($lblock);
    }
    
    public function getSlots($layout) {
        return $layout->ownLslot;
    }
    
    public function getBlocks($slot) {
        return $slot->sharedLblock;
    }
    
    public function getPublishedBlocks($slot) {
        $blocks = array();
        $items = $this->database_model->find('lblock_lslot', ' lslot_id = ?', array($slot->id));
        foreach ($items as $item) {
            $tblock = $this->database_model->load('lblock', $item->lblock_id);
            if ($tblock->publish) $blocks[] = $tblock;
        }
        usort($blocks, array($this, 'orderBlocks'));
        return $blocks;
    }
    
    public function delete($ids) {
        $this->database_model->delete('layout', $ids);
    }
    
    public function deleteModule($ids) {
        $this->database_model->delete('module', $ids);
    }
    
    public function deleteSlot($ids) {
        $this->database_model->delete('lslot', $ids);
    }
    
    public function deleteLblock($ids) {
        $this->database_model->delete('lblock', $ids);
    }
    
    private function orderBlocks($a, $b) {
        return ($a->publish_order > $b->publish_order);
    }
    
}
?>
