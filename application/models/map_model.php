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

class Map_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
    }
    
    public function create($title = 'New map', $description = 'Description', $alias = 'new_map')
    {
        $bean = $this->database_model->create('map');
        $bean->title = $title;
        $bean->description = $description;
        $bean->alias = $alias;
        $bean->last_update = date('Y-m-d H:i:s');
        return $bean;
    }
    
    public function addMapLayer(&$map, $layer) {
        $map->sharedLayer[] = $layer;
        $this->save($layer);
        $this->save($map);
    }
    
    public function save(&$bean)
    {   
        return $this->database_model->save($bean);
    }
    
    public function load($id) {
        return $this->database_model->load('map', $id);
    }
    
    public function loadAll() {
        return $this->database_model->find('map', ' true ');
    }
    
    public function loadAllByAccount($account) {
        $list = array();
        $groups = $account->sharedGroup;
        $maps = $this->database_model->find('map', ' true ');
        foreach ($maps as $map) {
            $tgroups = $map->fetchAs('account')->owner->sharedGroup;
            foreach ($tgroups as $tgroup) {
                foreach ($groups as $group) {
                    if ($tgroup == $group) $list[] = $map;
                }
            }
        }
        return $list;
    }
    
    public function loadByAlias($alias) {
        return $this->database_model->findOne('map', ' alias = ? ', array($alias));
    }
    
    public function addTags($bean, $list) {
        return $this->database_model->addTags($bean, $list);
    }
    
    public function setTags($bean, $list) {
        return $this->database_model->setTags($bean, $list);
    }
    
    public function getTags($bean) {
        return $this->database_model->getTags($bean);
    }
    
    public function findByTags($tags) {
        return $this->database_model->findByTags('map', $tags);
    }
    
    public function delete($ids) {
        $this->database_model->delete('map', $ids);
    }
    
}

?>
