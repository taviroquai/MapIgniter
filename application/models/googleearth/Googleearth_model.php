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

class Googleearth_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database_model');
        $this->load->model('map_model');
    }
    
    public function createLayerType($type = 'New Type') {
        $gelayertype = $this->database_model->create('gelayertype');
        $gelayertype->type = $type;
        return $gelayertype;
    }
    
    public function loadLayerType($id) {
        return $this->database_model->load('gelayertype', $id);
    }
    
    public function loadLayerTypeAll() {
        return $this->database_model->find('gelayertype', ' true ');
    }
    
    public function deleteLayerType($ids) {
        return $this->database_model->delete('gelayertype', $ids);
    }
    
    public function createLayer($layer, $gelayertype) {
        $gelayer = $this->database_model->create('gelayer');
        $gelayer->layer = $layer;
        $gelayer->gelayertype = $gelayertype;
        $gelayer->last_update = date('Y-m-d H:i:s');
        return $gelayer;
    }
    
    public function loadLayer($id) {
        return $this->database_model->load('gelayer', $id);
    }
    
    public function loadLayerAll() {
        return $this->database_model->find('gelayer', ' true ');
    }
    
    public function deleteLayer($ids) {
        return $this->database_model->delete('gelayer', $ids);
    }
    
    public function createMap($map) {
        $gemap = $this->database_model->create('gemap');
        $gemap->map = $map;
        $gemap->name = $map->title;
        return $gemap;
    }
    
    public function exportMap($gemap, $layers = true) {
        $map = array();
        $map['id'] = $gemap->id;
        
        $layers = array();
        $gelayers = $gemap->sharedGelayer;
        if (!empty($gelayers)) {
            foreach ($gelayers as &$gelayer) {
                $layers[] = $this->googleearth_model->exportLayer($gelayer);
            }
        }
        return array('map' => $map, 'layers' => $layers);
    }
    
    public function exportLayer($gelayer) {
        $export = array();
        $export['id'] = $gelayer->id;
        $export['name'] = $gelayer->layer->title;
        $export['alias'] = $gelayer->layer->alias;
        return $export;
    }
    
    public function loadMap($id) {
        return $this->database_model->load('gemap', $id);
    }
    
    public function loadMapAll() {
        return $this->database_model->find('gemap', ' true ');
    }
    
    public function deleteMap($ids) {
        return $this->database_model->delete('gemap', $ids);
    }
    
    public function addMapLayer(&$gemap, &$gelayer) {
        $gemap->sharedGelayer[]= $gelayer;
        $this->save($gemap);
    }
    
    public function delMapLayer(&$gemap, $ids) {
        $items = $gemap->sharedGelayer;
        foreach ($items as $item) {
            if (in_array($item->id, $ids)) unset($gemap->sharedGelayer[$item->id]);
        }
        $this->save($gemap);
    }
    
    public function save(&$bean)
    {
        return $this->database_model->save($bean);
    }

}

?>
