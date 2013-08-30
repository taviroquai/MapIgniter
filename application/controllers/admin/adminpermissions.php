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

class Adminpermissions extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'admin';
        $this->load->model('account/uriresource_model');
        $this->load->model('account/group_model');
    }
    
    /**
     * Action index
     * Display a list of Permissions
     * TODO: Pagination
     */
    public function index()
    {   
        // Load All Resources
        // TODO: Pagination
        $resources = $this->uriresource_model->loadAll();
        $groups = $this->group_model->loadAll();
        
        // Load main content
        $data = array('items' => $resources, 'groups' => $groups);
        $content = $this->load->view('admin/permissions/adminpermissions', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for uri resource edition
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load uri resource
            $uriresource = $this->uriresource_model->loadById($id);
            if (!$uriresource) throw new Exception('URI resource not found!');
            
            // Load main content
            $data = array('uriresource' => $uriresource);
            $content = $this->load->view('admin/permissions/adminedituriresource', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action editgroup
     * Opens a form for group edition
     * @param string $id 
     */
    public function editgroup($id)
    {   
        try {
            // Load group
            $group = $this->group_model->loadById($id);
            if (!$group) throw new Exception('User role not found!');
            
            // Load main content
            $uriresources = $this->uriresource_model->loadAll();
            $data = array('group' => $group, 'uriresources' => $uriresources);
            $content = $this->load->view('admin/permissions/admineditgroup', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edititem
     * Opens a form to edit group permission item
     * @param string $id 
     */
    public function edititem($id)
    {   
        try {
            // Load group permission item
            $permission = $this->group_model->loadPermissionById($id);
            if (!$permission) throw new Exception('Permission not found!');
                
            // Load main content
            $data = array('permission' => $permission);
            $content = $this->load->view('admin/permissions/adminedititem', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);

    }
    
    /**
     * Action save
     * Saves the new data of the uri resource
     * @param string $id 
     */
    public function save($id)
    {   
        try {
            // load post data
            $pattern = $this->input->post('pattern');
            
            // Create new uri resource
            if ($id === 'new') {
                $uriresource = $this->uriresource_model->create('/pattern/i');
            }
            // Load existing uri resource
            else {
                $uriresource = $this->uriresource_model->loadById($id);
                if (!$uriresource) throw new Exception('URI resource not found!');
            }

            // Set new data and save
            if (!empty($pattern)) {
                $uriresource->pattern = $pattern;
                $this->uriresource_model->save($uriresource);    
            }
        }
        catch(Exception $e) {
            //
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminpermissions/edit/'.$uriresource->id);
    }
    
    /**
     * Action savegroup
     * Saves the new data of the group
     * @param string $id 
     */
    public function savegroup($id)
    {   
        try {
            // load post data
            $name = $this->input->post('name');
            
            // Create new group
            if ($id === 'new') {
                $group = $this->group_model->create('new');
            }
            // Load existing group
            else {
                $group = $this->group_model->loadById($id);
                if (!$group) throw new Exception('User role not found!');
            }

            // Set new data and save
            if (!empty($name)) {
                $group->name = $name;
                $this->group_model->save($group);    
            }
        }
        catch(Exception $e) {
            //
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminpermissions/editgroup/'.$group->id);
    }
    
    /**
     * Action saveitem
     * Saves the new data of group permission
     * @param string $id 
     */
    public function saveitem($id)
    {   
        try {
            // Load post data
            $action = $this->input->post('action');
            $expire = $this->input->post('expire');

            // Create group permission
            if ($id === 'new') {
                // Load existing group
                $group_id = $this->input->post('group_id');
                if (!$group_id) throw new Exception('Invalid user role!');
                $group = $this->group_model->loadById($group_id);
                if (!$group) throw new Exception('User role not found!');
                $uriresource_id = $this->input->post('uriresource_id');
                if (!$uriresource_id) throw new Exception('URI resource is invalid!');
                $uriresource = $this->uriresource_model->loadById($uriresource_id);
                if (!$uriresource) throw new Exception('URI resource not found!');
                $permission = $this->group_model->addPermission($group, $uriresource, $action);
            }
            // Load existing group permission
            else {
                $permission = $this->group_model->loadPermissionById($id);
                if (!$permission) throw new Exception('Permission not found!');
            }
            
            // Set new data and save
            if (!empty($action)) {
                $permission->action = $action == 'allow' ? 1 : 0;
                $this->group_model->save($permission);
            }
            
            if (!empty($expire)) {
                $permission->expire = strtotime($expire);
                $this->group_model->save($permission);
            }
        }
        catch (Exception $e) {
            //
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminpermissions/edititem/'.$permission->id);
    }
    
    /**
     * Action delete
     * Deleted the selected uri resources
     */
    public function delete()
    {
        //R::debug(true);
        $selected = $this->input->post('selected');
        if (!empty($selected)) {
            foreach ($selected as $id) {
                $items[] = $this->group_model->loadPermissionByCriteria($id);
            }
            if (!empty($items)) {
                foreach ($items as $permissions) 
                    foreach ($permissions as $permission) $ids[] = $permission->id;
                $this->group_model->deletePermission($ids);
            }
            $this->uriresource_model->delete($selected);
        }
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminpermissions');
    }
    
    /**
     * Action deletegroup
     * Deletes the selected groups
     */
    public function deletegroup()
    {   
        $selected = $this->input->post('selected');
        $this->group_model->delete($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminpermissions');
    }
    
    /**
     * Action deleteitem
     * Deletes the selected group permission items
     */
    public function deleteitem()
    {   
        $group_id = $this->input->post('group_id');
        $selected = $this->input->post('selected');
        $this->group_model->deletePermission($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().'admin/adminpermissions/editgroup/'.$group_id);
    }
    
}