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

class Openlayers extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('openlayers/openlayers_model');
        $this->load->model('rating/rating_model');
    }
    
    public function index()
    {
        
    }
    
    public function getConfig($olmapid) {
        
        $this->load->model('openlayers/modmap_lblock');
        $main_block = $this->modmap_lblock;
        
        $olmap = $this->openlayers_model->loadMap($olmapid);
        $config = $this->openlayers_model->exportMap($olmap);
        
        header('Content-type: application/json');
        echo json_encode($config);
    }
    
    public function search($olmapid) {
        
        $results = array();
        $terms = $this->input->get('q');
        $jsinstance = 'block_'.$this->input->get('_instance');
        
        $olmap = $this->openlayers_model->loadMap($olmapid);
        $ollayers = $olmap->sharedOllayer;
        
        if (!empty($ollayers)) {
            $this->load->model('database/postgis_model');
            foreach ($ollayers as &$ollayer) {
                $pglayers = $ollayer->layer->ownPglayer;
                if (!empty($pglayers)) {
                    $pglayer = reset($pglayers);
                    $table = $this->postgis_model->loadTable($pglayer->pgplacetype);
                    $records = $this->postgis_model->findRecordsByTerms($table, $terms);
                    if (!empty($records)) $results[$ollayer->layer->alias] = 
                        array('pglayer' => $pglayer, 'records' => $records);
                }
            }
        }
        
        // Prepare data
        $data = array('results' => $results, '_instance' => $jsinstance);
        $content = $this->load->view('openlayers/searchresults', $data, TRUE);
        $this->render($content);
    }
    
    public function layerswitcher($olmapid, $instance) {
        $root = array('categories' => array(), 'layers' => array());

        $item = $this->openlayers_model->loadMap($olmapid);
        $ollayers = $item->sharedOllayer;
        if (!empty($ollayers)) {
            foreach ($ollayers as &$ollayer) {
                $parent = $ollayer->layer->fetchAs('layer')->parent;
                if (!empty($parent)) {
                    if (empty($root['categories'][$parent->id])) $root['categories'][$parent->id] = array('category' => $parent, 'layers' => array());
                    $root['categories'][$parent->id]['layers'][$ollayer->id] = $ollayer;
                }
                else {
                    $root['layers'][$ollayer->id] = $ollayer;
                }
            }
        }
        
        $data['root'] = $root;
        $data['instance'] = $instance;
        
        // Add rating items
        if (!empty($ollayers)) {
            foreach ($ollayers as $item) {
                $ratingitems[] = $item->layer->id;
            }
            $data['rating'] = 
            $this->rating_model->loadAll($ratingitems, 'layer', $this->account, $this->input->ip_address());
        }
        
        $content = $this->load->view('openlayers/layerswitcherlist', $data);
    }
    
    private function listlayers($layers, $instance) {
        foreach ($layers as $item) { ?>
        <li id="layer_<?=$item->layer->alias?>">
            <input type="checkbox" value="<?=$item->id?>" onclick="block_<?=$instance?>.toggle('<?=$item->layer->alias?>')" />
            <span class="milayer"><?=$item->layer->title?></span>
            <? $this->load->view('rate', array('rate' => $rating[$item->layer->id])); ?>
            <div>
                <? if ($item->ollayertype_id == 4) : ?>
                <img src="<?=base_url().'mapserver/map/'.$item->url?>?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetLegendGraphic&LAYER=<?=$item->layer->alias?>&FORMAT=image/png" />
                <? endif; ?>
            </div>
        </li>
        <? }
    }

    public function printmap()
    {

        /**
         * Load Openlayers model
         */
        $this->load->model('openlayers/openlayers_model');

        /**
         * Rebuild map image
         * 
         */
        $imageurl = $this->openlayers_model->printmap($_REQUEST, $_SERVER);

        echo sprintf('<a href="%s" target="_blank">Descarregar</a>', $imageurl);
    }
}