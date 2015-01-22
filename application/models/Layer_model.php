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

class Layer_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
    }
    
    public function create($title = 'New layer', $description = 'Description', $alias = 'new_layer')
    {
        $bean = $this->database_model->create('layer');
        $bean->title = $title;
        $bean->description = $description;
        $bean->alias = $alias;
        $bean->last_update = date('Y-m-d H:i:s');
        return $bean;
    }
    
    public function save(&$bean)
    {   
        return $this->database_model->save($bean);
    }
    
    public function load($id) {
        return $this->database_model->load('layer', $id);
    }
    
    public function loadByAlias($alias) {
        return $this->database_model->findOne('layer', ' alias = ? ', array($alias));
    }
    
    public function loadAll() {
        return $this->database_model->find('layer', ' true ');
    }
    
    public function loadAllByAccount($account) {
        $list = array();
        $groups = $account->sharedGroup;
        $layers = $this->database_model->find('layer', ' true ');
        foreach ($layers as $layer) {
            $tgroups = $layer->fetchAs('account')->owner->sharedGroup;
            foreach ($tgroups as $tgroup) {
                foreach ($groups as $group) {
                    if ($tgroup == $group) $list[] = $layer;
                }
            }
        }
        return $list;
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
        return $this->database_model->findByTags('layer', $tags);
    }
    
    public function delete($ids) {
        // Clean dependencies
        $this->load->model('database/postgis_model');
        $this->load->model('mapserver/mapserver_model');
        $this->load->model('openlayers/openlayers_model');
        foreach($ids as $id) {
            $layer = $this->load($id);
            
            // delete Postgis layers
            $pglayers = $layer->ownPglayer;
            foreach ($pglayers as $pglayer) $pglayers_ids[] = $pglayer->id;
            if (!empty($pglayers_ids)) $this->postgis_model->deleteLayer($pglayers_ids);
            
            // delete MapServer layers
            $mslayers = $layer->ownMslayer;
            foreach ($mslayers as $mslayer) $mslayers_ids[] = $mslayer->id;
            if (!empty($mslayers_ids)) $this->mapserver_model->deleteLayer($mslayers_ids);
            
            // delete OpenLayers layers
            $ollayers = $layer->ownOllayer;
            foreach ($ollayers as $ollayer) $ollayers_ids[] = $ollayer->id;
            if (!empty($ollayers_ids)) $this->openlayers_model->deleteLayer($ollayers_ids);
        }
        // Delete layer
        $this->database_model->delete('layer', $ids);
    }
    
}

?>
