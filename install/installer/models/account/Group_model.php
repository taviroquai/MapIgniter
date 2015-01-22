<?php


/**
 * MapIgniter
 *
 * An open source GeoCMS application
 *
 * @package		MapIgniter
 * @author		Marco Afonso
 * @copyright	Copyright (c) 2012-2013, Marco Afonso
 * @license		dual license, one of two: Apache v2 or GPL
 * @link		http://mapigniter.com/
 * @since		Version 1.1
 * @filesource
 */

// ------------------------------------------------------------------------

class Group_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
    }
    
    public function create($name)
    {   
        $bean = $this->database_model->create('group');
        $bean->name = $name;
        return $bean;
    }
    
    public function loadAll() {
        return $this->database_model->find('group', ' true ');
    }
    
    public function loadById($id) {
        return $this->database_model->findOne('group', ' id = ? ', array($id));
    }
    
    public function load($name) {
        return $this->database_model->findOne('group', ' name = ? ', array($name));
    }
    
    public function loadPermissionById($id) {
        return $this->database_model->load('permission', $id);
    }
    
    public function loadPermissionByCriteria($id) {
        return $this->database_model->find('permission', ' uriresource_id = ? ', array($id));
    }
    
    public function delete($ids) {
        $this->database_model->delete('group', $ids);
    }
    
    public function deletePermission($ids) {
        $this->database_model->delete('permission', $ids);
    }
    
    public function save(&$bean)
    {
        return $this->database_model->save($bean);
    }
    
    public function addAccount(&$group, &$account)
    {
        $group->sharedAccount[] = $account;
        $this->database_model->save($group);
        $this->database_model->save($account);
    }
    
    public function addPermission(&$group, &$uriresource, $action, $expire = 0) {
        $permission = $this->database_model->create('permission');
        $permission->uriresource = $uriresource;
        $action = $action == 'allow' ? 1 : 0; // 1 = allow; 0 = deny
        $permission->action = $action;
        $permission->expire = $expire;
        $group->sharedUriresource[] = $permission;
        $this->save($group);
        return $permission;
    }
    
    public function addChildGroup(&$parent, &$child) {
        $parent->ownGroup[] = $child;
        $this->save($parent);
    }
    
    public function accountGroups($account) {
        return R::related($account, 'group');
    }
    
    public function isAccountAllowed($account, $uri) {

        $final = true;
        $groups = $this->accountGroups($account);
        if (empty($groups)) return true;
        foreach ($groups as $group) {
            $permissions = $this->getPermissions($group);
            foreach ($permissions as $permission) {
                if ($permission->expire > 0 && $permission->expire < time()) continue;
                $pattern = $permission->uriresource->pattern;
                $result = preg_match($pattern, $uri);
                if ($result > 0) {
                    $final = $final & (boolean) $permission->action;
                }
            }
        }
        return $final;
    }
    
    protected function getPermissions($group) {
        return $group->sharedPermission;
    }
    
}

?>
