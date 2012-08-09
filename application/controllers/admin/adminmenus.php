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

class Adminmenus extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('admin/modmenu_model');
    }
    
    /**
     * Action index
     * Display a list of Menus
     * TODO: Pagination
     */
    public function index()
    {   
        // Load All Menus
        // TODO: Pagination
        $menus = $this->modmenu_model->loadAll();
        
        // Load main content
        $content = $this->load->view('admin/menus/adminmenus', array('items' => $menus), TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for menu edition
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load menu
            $menu = $this->modmenu_model->loadById($id);
            if (!$menu) throw new Exception('Menu not found!');
            
            // Create a menuitem
            $menuitem = $this->modmenu_model->addItem('new', base_url(), 1, $menu);
            
            // Load menuitems
            $items = $this->modmenu_model->loadItemAll($menu);
            
            // Load main content
            $data = array('menu' => $menu, 'menuitem' => $menuitem, 'items' => $items);
            $content = $this->load->view('admin/menus/admineditmenu', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edititem
     * Opens a form for menu item edition
     * @param string $id 
     */
    public function edititem($id)
    {   
        try {
            // Load menu item
            $menuitem = $this->modmenu_model->loadItemById($id);
            if (!$menuitem) throw new Exception('Menu item not found!');
                
            // Load main content
            $data = array('menuitem' => $menuitem);
            $content = $this->load->view('admin/menus/admineditmenuitem', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);

    }
    
    /**
     * Action save
     * Saves the new data of the menu
     * @param string $id 
     */
    public function save($id)
    {   
        try {
            // load post data
            $name = $this->input->post('name');
            
            // Create new menu
            if ($id === 'new') {
                $menu = $this->modmenu_model->create('new');
                $account = $this->account_model->load($this->session->userdata('username'));
                $menu->owner = $account;
            }
            // Load existing menu
            else {
                $menu = $this->modmenu_model->loadById($id);
                if (!$menu) throw new Exception('Menu not found!');
            }

            // Set new data and save
            if (!empty($name)) {
                $menu->name = $this->input->post('name');
                $this->modmenu_model->save($menu);    
            }
        }
        catch(Exception $e) {
            //
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminmenus/edit/'.$menu->id);
    }
    
    /**
     * Action saveitem
     * Saves the new data of the menu item
     * @param string $id 
     */
    public function saveitem($id)
    {   
        try {
            // Load post data
            $label = $this->input->post('label');
            $href = $this->input->post('href');
            $internal = $this->input->post('internal');
            $listorder = $this->input->post('listorder');

            // Create menuitem
            if ($id === 'new') {
                // Load existing menu
                $menu_id = $this->input->post('menu_id');
                $menu = $this->modmenu_model->loadById($menu_id);
                if (!$menu) throw new Exception('Menu not found!');
                $menuitem = $this->modmenu_model->addItem($label, $href, $internal, $menu);
                $account = $this->account_model->load($this->session->userdata('username'));
                $menuitem->owner = $account;
            }
            // Load existing menuitem
            else {
                $menuitem = $this->modmenu_model->loadItemById($id);
                if (!$menuitem) throw new Exception('Item menu not found!');
            }
            
            // Set new data and save
            if (!empty($label) && !empty($href)) {
                $menuitem->label = $label;
                $menuitem->href = $href;
                $menuitem->internal = empty($internal) ? 0 : 1;
                $menuitem->listorder = empty($listorder) ? 1 : $listorder;
                $this->modmenu_model->save($menuitem);
            }
        }
        catch (Exception $e) {
            echo "<p>{$e->getMessage()}</p>";
        }
        if (!$this->input->is_ajax_request()) 
            redirect(base_url().'admin/adminmenus/edititem/'.$menuitem->id);
    }
    
    /**
     * Action delete
     * Deleted the selected menus
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->modmenu_model->delete($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminmenus');
    }
    
    /**
     * Action deleteitem
     * Deletes the selected menu items
     */
    public function deleteitem()
    {   
        $menu_id = $this->input->post('menu_id');
        $selected = $this->input->post('selected');
        $this->modmenu_model->deleteItems($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminmenus/edit/'.$menu_id);
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