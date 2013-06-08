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

/**
 * Description of Mapserver_model
 * 
 * Reference
 * http://mapserver.org/mapfile/map.html
 * 
 *
 * @author mafonso
 */
class Mapserver_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database_model');
        $this->load->model('map_model');
    }
    
    /**
     * Translates mapfile to correct base path
     * Server path should be invisible for security
     * @param array $request
     * @param array $query
     * @return string 
     */
    public function translateUrl($alias, $query) {
        $url = $this->config->item('mapserver_cgi');
        if (!empty($alias)) {
            $newmap = $this->config->item('private_data_path').'mapfile/'.$alias.'.map&';
            $url = $url . 'map='.$newmap.$query;
        }
        return $url;
    }
    
    /**
     * Generates an updated mapfile from database configuration
     * @param integer $id
     */
    public function updateMapfile($id) {
        try {
            $maps_path = $this->config->item('private_data_path').'/mapfile/';

            if (empty($id)) throw new Exception('Empty map name');
            $mapfile = $this->mapserver_model->loadMapfile($id);
            $this->mapserver_model->save($mapfile);
            if (empty($mapfile)) throw new Exception('No such map on database');
            $mapfile_path = $maps_path.$mapfile->map->alias.'.map';
            //echo "<p>Generating mapfile $mapfile_path ...</p>";
            
            $private_data_path = $this->config->item('private_data_path');
            ob_start();
            include_once 'template.php';
            file_put_contents($mapfile_path, ob_get_clean());
            //echo "<p>Done!</p>";
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function createUnits($name) {
        $bean = $this->database_model->create('msunits');
        $bean->name = $name;
        return $bean;
    }
    
    public function loadUnitsAll() {
        return $this->database_model->find('msunits', ' true order by name');
    }
    
    public function loadUnits($id) {
        return $this->database_model->load('msunits', $id);
    }
    
    public function deleteUnits($ids) {
        $this->database_model->delete('msunits', $ids);
    }
    
    public function createMetadata($name) {
        $bean = $this->database_model->create('msmetadata');
        $bean->name = $name;
        return $bean;
    }
    
    public function loadMetadata($id) {
        return $this->database_model->load('msmetadata', $id);
    }
    
    public function loadMetadataAll() {
        return $this->database_model->find('msmetadata', ' true order by name');
    }
    
    public function deleteMetadata($ids) {
        $this->database_model->delete('msmetadata', $ids);
    }
    
    public function addMapfileMetadata($mapfile, $metadata, $value) {
        $bean = $this->database_model->create('msmapfilemd');
        $bean->msmetadata = $metadata;
        $bean->msmapfile = $mapfile;
        $bean->value = $value;
        $this->save($bean);
        return $bean;
    }
    
    public function setMapfileMetadata($id, $value) {
        $bean = $this->database_model->load('msmapfilemd', $id);
        if ($bean) {
            $bean->value = $value;
            $this->save($bean);
        }
        return $bean;
    }
    
    public function loadMapfile($id) {
        return $this->database_model->load('msmapfile', $id);
    }
    
    public function loadMapfileAll($id) {
        return $this->database_model->load('msmapfile', $id);
    }

    public function createMapfile($map, $extent = '-180.0000 -90.0000 180.0000 90.0000', $projection = 'init=epsg:4326') {
        
        $mapfile = $this->database_model->create('msmapfile');
        $mapfile->map = $map;
        $mapfile->extent = $extent;
        $mapfile->projection = $projection;
        $mapfile->msunits = $this->database_model->findOne('msunits', ' name = ? ', array('meters'));
        $mapfile->sizex = 400;
        $mapfile->sizey = 400;
        $mapfile->debug = 'off';
        $mapfile->fontset = '.';
        $mapfile->symbolset = '.';
        $mapfile->imagecolor = '255 255 255';
        $mapfile->imagetype = 'PNG';
        $mapfile->last_update = date('Y-m-d H:i:s');
        
        return $mapfile;
    }
    
    public function save(&$bean)
    {
        return $this->database_model->save($bean);
    }
    
    public function deleteMapfileMetadata($ids) {
        $this->database_model->delete('msmapfilemd', $ids);
    }
    
    public function createLegend($msmapfile) {
        $mslegend = $this->database_model->create('mslegend');
        $mslegend->msmapfile = $msmapfile;
        $mslegend->imagecolor = '255 255 255';
        $mslegend->keysize = '20 15';
        $mslegend->keyspacing = '5 5';
        $mslegend->outlinecolor = '';
        $mslegend->position = 'lr';
        $mslegend->postlabelcache = 'false';
        $mslegend->template = '.';
        $mslegend->status = 'on';
        return $mslegend;
    }
    
    public function loadLegendAll() {
        return $this->database_model->find('mslegend', ' true order by name');
    }
    
    public function loadLegend($id) {
        return $this->database_model->load('mslegend', $id);
    }
    
    public function deleteLegend($ids) {
        $this->database_model->delete('mslegend', $ids);
    }
    
    public function addLegendLabel(&$mslegend, $mslabel) {
        $mslegend->mslabel = $mslabel;
        $this->save($mslegend);
        return $mslegend;
    }
    
    public function createLayerType($name) {
        $bean = $this->database_model->create('mslayertype');
        $bean->name = $name;
        return $bean;
    }
    
    public function loadLayerType($id) {
        return $this->database_model->load('mslayertype', $id);
    }
    
    public function loadLayerTypeAll() {
        return $this->database_model->find('mslayertype', ' true ');
    }
    
    public function createLayerConnectionType($name) {
        $bean = $this->database_model->create('mslayerconntype');
        $bean->name = $name;
        return $bean;
    }
    
    public function loadLayerConnectionType($id) {
        return $this->database_model->load('mslayerconntype', $id);
    }
    
    public function loadLayerConnectionTypeAll() {
        return $this->database_model->find('mslayerconntype', ' true order by name');
    }
    
    public function deleteLayerConnectionType($ids) {
        $this->database_model->delete('mslayerconntype', $ids);
    }
        
    public function createLayer($layer, $extent = '', $projection = '') {
        
        // check for unique $mslayer using $layer
        $exists = $this->database_model->find('mslayer', ' layer_id = ? ', array($layer->id));
        if (count($exists)) throw new Exception ('There is already a mapserver layer using this layer.');

        $maps_path = $this->config->item('private_data_path').'mapfile/';
        $mslayer = $this->database_model->create('mslayer');
        $mslayer->layer = $layer;
        $mslayer->msunits = $this->database_model->findOne('msunits', ' name = ? ', array('pixels'));
        $mslayer->extent = $extent;
        $mslayer->projection = $projection;
        $mslayer->pgplacetype = '';
        $mslayer->mslayerconntype = $this->database_model->findOne('mslayerconntype', ' name = ? ', array('local'));
        $mslayer->connection = '';
        $mslayer->dump = 'true';
        $mslayer->status = 'on';
        $mslayer->opacity = 100;
        $mslayer->symbolscaledenom = '';
        $mslayer->maxscaledenom = '';
        $mslayer->minscaledenom = '';
        $mslayer->labelitem = '';
        $mslayer->classitem = '';
        $mslayer->last_update = date('Y-m-d H:i:s');
        
        return $mslayer;
    }
    
    public function loadLayer($id) {
        return $this->database_model->load('mslayer', $id);
    }
    
    public function loadLayerAll() {
        return $this->database_model->find('mslayer', ' true ');
    }
    
    public function findPgLayer($tablename) {
        return $this->database_model->find('mslayer', " mslayerconntype = 5 and data ilike ? ", array("%$tablename%"));
    }
    
    public function deleteLayer($ids) {
        // Clean dependencies
        foreach($ids as $id) {
            $mslayer = $this->loadLayer($id);
            $classes = $mslayer->ownMsclass;
            foreach ($classes as $msclass) $msclass_ids[] = $msclass->id;
            $this->deleteClass($msclass_ids);
            $mslayer->ownMsclass = array();
            $this->save($mslayer);
        }
        // Delete mapserver layer
        $this->database_model->delete('mslayer', $ids);
    }
    
    public function addMapfileLayer(&$mapfile, &$mslayer) {
        $mapfile->sharedMslayer[]= $mslayer;
        $this->save($mapfile);
    }
    
    public function delMapfileLayer(&$mapfile, $ids) {
        $items = $mapfile->sharedMslayer;
        foreach ($items as $item) {
            if (in_array($item->id, $ids)) unset($mapfile->sharedMslayer[$item->id]);
        }
        $this->save($mapfile);
    }
    
    public function addLayerMetadata($mslayer, $metadata, $value) {
        $bean = $this->database_model->create('mslayermd');
        $bean->msmetadata = $metadata;
        $bean->mslayer = $mslayer;
        $bean->value = $value;
        $this->save($bean);
        return $bean;
    }
    
    public function loadLayerMetadata($id) {
        return $this->database_model->load('mslayermd', $id);
    }
    
    public function deleteLayerMetadata($ids) {
        $this->database_model->delete('mslayermd', $ids);
    }

    public function createClass($mslayer, $name) {
        $msclass = $this->database_model->create('msclass');
        $msclass->mslayer = $mslayer;
        $msclass->name = $name;
        $msclass->expression = '';
        $msclass->status = 'on';
        $msclass->color = '';
        $msclass->bgcolor = '';
        $msclass->outlinecolor = '';
        $msclass->debug = 'off';
        $msclass->maxscaledenom = '';
        $msclass->minscaledenom = '';
        $msclass->symbol = '';
        $msclass->size = '';
        $msclass->text = '';
        return $msclass;
    }
    
    public function loadClass($id) {
        return $this->database_model->load('msclass', $id);
    }
    
    public function loadClassAll() {
        return $this->database_model->find('msclass', ' true ');
    }
    
    public function deleteClass($ids) {
        $this->database_model->delete('msclass', $ids);
    }
    
    public function createStyle($description = 'New style - Description') {
        $msstyle = $this->database_model->create('msstyle');
        $msstyle->description = $description;
        
        $msstyle->angle = 'AUTO';
        $msstyle->antialias = 'false';
        $msstyle->bgcolor = '255 255 255';
        $msstyle->color = '30 30 30';
        $msstyle->gap = 0;
        $msstyle->geomstransform = '';
        $msstyle->linecap = 'round';
        $msstyle->linejoin = 'round';
        $msstyle->linejoinmaxsize = 3;
        $msstyle->maxsize = 500;
        $msstyle->maxwidth = 32;
        $msstyle->minsize = 0;
        $msstyle->minwidth = 0;
        $msstyle->offset = '0 0';
        $msstyle->opacity = '100';
        $msstyle->outlinecolor = '210 210 210';
        $msstyle->pattern = '1 0';
        $msstyle->size = 10.0;
        $msstyle->symbol = 'circle';
        $msstyle->width = 1;
        
        return $msstyle;
    }
    
    public function loadStyle($id) {
        return $this->database_model->load('msstyle', $id);
    }
    
    public function loadStyleAll() {
        return $this->database_model->find('msstyle', ' true ');
    }
    
    public function deleteStyle($ids) {
        $this->database_model->delete('msstyle', $ids);
    }
    
    public function addClassStyle(&$msclass, &$msstyle) {
        $msclass->sharedMsstyle[] = $msstyle;
        $this->save($msclass);
        $this->save($msstyle);
    }
    
    public function deleteClassStyle(&$msclass, $ids) {
        $msstyles = $msclass->sharedMsstyle;
        $list = array();
        foreach ($msstyles as &$msstyle) {
            foreach ($ids as &$id) {
                if ($msstyle->id != $id) $list[] = $msstyle;
            }
        }
        $msclass->sharedMsstyle = $list;
        $this->save($msclass);
    }
    
    public function createLabel($description = 'New Label - Description') {
        $mslabel = $this->database_model->create('mslabel');
        $mslabel->description = $description;
        
        $mslabel->align = 'left';
        $mslabel->angle = 'auto';
        $mslabel->antialias = 'true';
        $mslabel->buffer = 0;
        $mslabel->color = '30 30 30';
        $mslabel->encoding = 'UTF-8';
        $mslabel->font = 'arial';
        $mslabel->force = 'false';
        $mslabel->maxlength = 0;
        $mslabel->maxoverlapangle = 22.5;
        $mslabel->maxsize = 256;
        $mslabel->mindistance = 10;
        $mslabel->minfeaturesize = 'auto';
        $mslabel->minsize = 4;
        $mslabel->offset = '0 0';
        $mslabel->outlinecolor = '255 255 255';
        $mslabel->outlinewidth = 1;
        $mslabel->partials = 'false';
        $mslabel->position = 'auto';
        $mslabel->priority = 1;
        $mslabel->repeatdistance = '';
        $mslabel->shadowcolor = '';
        $mslabel->shadowsize = '';
        $mslabel->size = 9;
        $mslabel->style = '';
        $mslabel->type = 'truetype';
        $mslabel->wrap = '';
        return $mslabel;
    }
    
    public function loadLabel($id) {
        return $this->database_model->load('mslabel', $id);
    }
    
    public function loadLabelAll() {
        return $this->database_model->find('mslabel', ' true ');
    }
    
    public function deleteLabel($ids) {
        $this->database_model->delete('mslabel', $ids);
    }
    
    public function addClassLabel(&$msclass, &$mslabel) {
        $msclass->sharedMslabel[] = $mslabel;
        $this->save($msclass);
        $this->save($mslabel);
    }
    
    public function deleteClassLabel(&$msclass) {
        $msclass->sharedMslabel = array();
        $this->save($msclass);
    }

}

?>