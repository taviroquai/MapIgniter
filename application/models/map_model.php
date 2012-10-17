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
    
    public function createAuto($map, $post, &$errors, &$info) {
        
        // Load all necessary models
        $this->load->model('database/postgis_model');
        $this->load->model('layer_model');
        $this->load->model('mapserver/mapserver_model');
        $this->load->model('openlayers/openlayers_model');
        
        // Setup default values
        switch($post['type']) {   
            case 'LINESTRING': $post['mslayertype_id'] = 4; break;
            case 'POLYGON': $post['mslayertype_id'] = 6; break;
            default: $post['mslayertype_id'] = 5;
        }
            
        $post['mslayerconntype_id'] = 5; // MapServer layer WMS connection type
        $mslabel_id = 1; // MapServer default label
        $msstyle_id = 1; // MapServer default style
        $msunits_id = 5; // MapServer default units
        $ollayertype_id = 4; // OpenLayers default layer type (WMS Internal)
        $olbaselayer_id = 1; // OpenLayers default map base layer (OSM)
        $account = $map->fetchAs('account')->owner;
        
        // Create a layer
        $layer = $this->layer_model->create();
        $layer->title = $map->title;
        $layer->alias = $map->alias;
        $layer->description = $map->description;
        $layer->owner = $account;
        $this->database_model->save($layer);
        $info[] = 'The layer was created';
        
        // Create a layer in Postgis
        $pglayer = $this->postgis_model->createLayer($layer);
        $pglayer->owner = $account;
        $table = $this->postgis_model->createTable($layer->alias);
        
        // Create Postgis table
        $tablefields = array(
            'srid' => $post['srid'],
            'type' => $post['type']);
        if ($post['pgplacetype'] == 'new_pgplacetype') {
            $post['new_pgplacetype'] = $layer->alias;
            $post['pgplacetype'] = $post['new_pgplacetype'];
            $table = $this->postgis_model->createTable($post['pgplacetype']);
            $this->postgis_model->import($table, $tablefields, 'srid,type');
            $this->postgis_model->saveTable($table);
            $info[] = 'Postgis table was created';
        }
        else {
            $table = $this->postgis_model->loadTable($post['pgplacetype']);
        }
        
        // Get table extent
        $table_extent = $this->postgis_model->getTableExtent($post['pgplacetype']);

        $pglayer->srid = $table->srid;
        $pglayer->pgplacetype = $table->name;
        $this->postgis_model->saveLayer($pglayer);
        $info[] = 'Postgis layer was created';
        
        // Create MapServer layer
        $mslayer = $this->mapserver_model->createLayer($layer);
        $mslayer->owner = $account;
        
        // Import from Postgis layer
        $dbconfig = $this->database_model->getConfig('userdata');
        $post['connection'] = "host={$dbconfig['hostname']} user={$dbconfig['username']} password={$dbconfig['password']} dbname={$dbconfig['database']}";
        $post['data'] = "the_geom FROM {$post['pgplacetype']} USING UNIQUE gid USING srid={$table->srid}";
        $post['projection'] = "init=epsg:{$table->srid}";
        
        switch ($post['srid']) {
            case 900913:
            case 3857: $post['extent'] = '-20037508.34 -20037508.34 20037508.34 20037508.34'; break;
            default: $post['extent'] = '-180 -90 180 90';
        }
        $map_extent = $post['extent'];
        $post['extent'] = empty($table_extent['error']) ? $table_extent['extent'] : $post['extent'];
        $post['status'] = 'on';
        $post['dump'] = 'true';
        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(6), 'value' => 'all');
        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(8), 'value' => 'EPSG:'.$post['srid']);
        $addclass[] = array('name' => 'myclass');
        
        // Save MapServer layer
        $fields = array(
            'pgplacetype',
            'extent',
            'projection',
            'connection',
            'dump',
            'status',
            'data'
        );
        $mslayer->import($post, implode(',', $fields));
        if (!empty($post['new_pgplacetype'])) $mslayer->labelitem = 'title';
        $mslayer->msunits = $this->mapserver_model->loadUnits($msunits_id);
        $mslayer->mslayerconntype = $this->mapserver_model->loadLayerConnectionType($post['mslayerconntype_id']);
        $mslayer->mslayertype = $this->mapserver_model->loadLayerType($post['mslayertype_id']);
        $this->database_model->save($mslayer);
        $info[] = 'MapServer layer was created';
        
        // Load default label
        $mslabel = $this->mapserver_model->loadLabel($mslabel_id);
        
        // Load default style
        $msstyle = $this->mapserver_model->loadStyle($msstyle_id);
        
        // Add metadata items to MapServer layer
        if (!empty($addmetadata)) {
            foreach ($addmetadata as $item) {
                $this->mapserver_model->addLayerMetadata($mslayer, $item['metadata'], $item['value']);
                $info[] = 'Metadata '.$item['metadata']->name.' item was created and added';
            }
        }

        // Add a feature class to MapServer layer
        if (!empty($addclass)) {
            foreach ($addclass as $item) {
                $msclass = $this->mapserver_model->createClass($mslayer, $item['name']);
                $msclass->owner = $account;
                $this->mapserver_model->save($msclass);
                $info[] = 'Class '.$item['name'].' was created and added';
                
                // Add label to class
                $this->mapserver_model->addClassLabel($msclass, $mslabel);
                $info[] = 'Label '.$mslabel->description.' added to class '.$item['name'];
                
                // Add style to class
                $this->mapserver_model->addClassStyle($msclass, $msstyle);
                $info[] = 'Style '.$msstyle->description.' added to class '.$item['name'];
            }
        }
        
        // Load default mapfile units
        $msunits = $this->mapserver_model->loadUnits($msunits_id);
        
        // Create Mapfile
        $msmap = $this->mapserver_model->createMapfile($map);
        $msmap->fontset = './mapfile/fonts/fonts.list';
        $msmap->symbolset = './mapfile/symbols/symbols.txt';
        $msmetadata = $this->mapserver_model->loadMetadata(9);
        $this->mapserver_model->addMapfileMetadata($msmap, $msmetadata, '*');
        $msmap->owner = $account;
        $msmap->msunits = $msunits;
        $msmap->projection = "init=epsg:{$post['srid']}";;
        $msmap->extent = $map_extent;
        $this->database_model->save($msmap);
        $info[] = 'The map on mapserver was created';
        
        // Add mapfile metadata
        $addmetadata = array();
        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(1), 'value' => 'UTF8');
        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(2), 'value' => 'Auto map');
        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(3), 'value' => 'No info');
        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(4), 'value' => 'EPSG:'.$pglayer->srid);
        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(5), 'value' => 'mapserver?map='.$map->alias.'.map');
        $addmetadata[] = array('metadata' => $this->mapserver_model->loadMetadata(10), 'value' => 'text/html');
        if (!empty($addmetadata)) {
            foreach ($addmetadata as $item) {
                $this->mapserver_model->addMapfileMetadata($msmap, $item['metadata'], $item['value']);
                $info[] = 'Metadata '.$item['metadata']->name.' item was created and added to the map '.$map->alias;
            }
        }
        
        // Create MapServer Legend
        $mslegend = $this->mapserver_model->createLegend($msmap);
        $mslegend->template = './mapfile/template/shape_legend_body.html';
        $mslegend->owner = $account;
        $this->mapserver_model->save($mslegend);
        $info[] = 'The mapserver legend was created';
        
        // Add Label to MapServer Legend
        $this->mapserver_model->addLegendLabel($mslegend, $mslabel);
        $info[] = 'The mapserver legend was added to the mapfile';
        
        // Add layer to Mapfile
        $this->mapserver_model->addMapfileLayer($msmap, $mslayer);
        $info[] = 'The mapfile layer was added to the map';
        
        // Update MapServer map
        $this->database_model->save($msmap);
        $this->mapserver_model->updateMapfile($msmap->id);
        $info[] = 'Updating mapfile...';
        
        // Load OpenLayers layer type
        $ollayertype = $this->openlayers_model->loadLayerType($ollayertype_id);
        
        // Create OpenLayers layer
        $opts = "{\n\"isBaseLayer\": false,\n\"gutter\": 15\n}";
        $vendoropts = "{\n\"layers\":\"".$layer->alias."\",\n\"transparent\": true,\n\"projection\":\"EPSG:{$pglayer->srid}\"\n}";
        $url = $map->alias;
        $ollayer = $this->openlayers_model->createLayer($layer, $ollayertype, $url, $opts, $vendoropts);
        $ollayer->owner = $account;
        $this->database_model->save($ollayer);
        $info[] = 'The OpenLayers layer was created';
        
        // Create OpenLayers map
        $olmap = $this->openlayers_model->createMap($map);
        $olmap->projection = 'EPSG:'.$post['srid'];
        $olmap->owner = $account;
        $this->database_model->save($olmap);
        $info[] = 'The OpenLayers map was created';
        
        // Load base OpenLayers layer
        $olbase = $this->openlayers_model->loadLayer($olbaselayer_id);
        
        // Add layers to OpenLayers map
        $this->openlayers_model->addMapLayer($olmap, $olbase);
        $info[] = "The base layer was added to OpenLayers map";
        $this->openlayers_model->addMapLayer($olmap, $ollayer);
        $info[] = "The {$layer->alias} layer was added to OpenLayers map";
        
        // Add layers to map
        $osm = $this->layer_model->loadByAlias('osm1');
        $this->map_model->addMapLayer($map, $osm);
        $this->map_model->addMapLayer($map, $layer);
        
        return $pglayer;
    }
}

?>
