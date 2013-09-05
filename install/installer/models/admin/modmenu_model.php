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

class Modmenu_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
    }
    
    public function create($name)
    {
        
        $bean = $this->database_model->create('modmenu');
        $bean->name = $name;
        
        return $bean;
    }
    
    public function addItem($label, $href, $internal, &$menu, $listorder = null)
    {
        $bean = $this->database_model->create('modmenuitem');
        $bean->label = $label;
        $bean->href = $href;
        $bean->internal = empty($internal) ? 0 : 1;
        $bean->listorder = $listorder;
        if (empty($listorder)) {
            $count = count($menu->ownModmenuitem);
            $bean->listorder = $count+1;
        }
        $bean->modmenu = $menu;
        return $bean;
    }
    
    public function save(&$bean)
    {   
        return $this->database_model->save($bean);
    }
    
    public function load($name) {
        return $this->database_model->findOne('modmenu', ' name = ? ', array($name));
    }
    
    public function loadAll() {
        return $this->database_model->find('modmenu', ' true ');
    }
    
    public function loadById($id) {
        return $this->database_model->findOne('modmenu', ' id = ? ', array($id));
    }
    
    public function loadItemById($id) {
        return $this->database_model->findOne('modmenuitem', ' id = ? ', array($id));
    }
    
    public function loadItemAll($modmenu) {
        return $this->database_model->find('modmenuitem', ' modmenu_id = ? order by listorder ', array($modmenu->id));
    }
    
    public function delete($ids) {
        $this->database_model->delete('modmenu', $ids);
    }
    
    public function deleteItems($ids) {
        $this->database_model->delete('modmenuitem', $ids);
    }
    
}

?>
