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

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->layout = 'registered';
    }
    
    public function index()
    {
        // Load models
        $this->load->model('stats/stats_model');
        $this->load->model('rating/rating_model');
        $this->load->model('database/postgis_model');
        $this->load->model('layer_model');
        
        // Get Top 10 Layers
        $data['layers'] = array();
        $top10layers = $this->stats_model->top10Rated('layer');
        if (!empty($top10layers)) {
            foreach ($top10layers as $item) $layers_ids[] = $item['entityid'];
            $data['layers'] = $this->database_model->loadAll('layer', $layers_ids);
        
            // Get Layers Rating
            foreach ($top10layers as $item) {
                $ratingitems[] = $item['entityid'];
            }
            $data['layers_rating'] = 
            $this->rating_model->loadAll($ratingitems, 'layer', $this->account, $this->input->ip_address());
        }
        
        // Get top 10 locals
        $data['locals'] = array();
        $top10locals = $this->stats_model->top10Rated('pgplace');
        if (!empty($top10locals)) {
            foreach ($top10locals as $item) {
                $attrs = explode('.', $item['entityid']);
                $layeralias = reset($attrs);
                $id = end($attrs);
                
                // Load Layer
                $layer = $this->layer_model->loadByAlias($layeralias);
                if (!$layer) continue;
                $pglayer = $this->database_model->findOne('pglayer', ' layer_id = ? ', array($layer->id));
                $data['locals'][$layeralias]['pglayer'] = $pglayer;
                $table = $this->postgis_model->loadTable($pglayer->pgplacetype);
                $record = $this->postgis_model->loadRecords($table, ' gid = ? ', array($id), 1);
                if (empty($record)) continue;
                $data['locals'][$layeralias]['records'] = $record;
            
                // Add to Locals Rating
                $ratingitems[] = $item['entityid'];
            }
            $data['locals_rating'] = 
            $this->rating_model->loadAll($ratingitems, 'pgplace', $this->account, $this->input->ip_address());
        }
        
        // Load main content
        $content = $this->load->view('user/content', $data, TRUE);
        
        // Load layout and render
        $this->render($content);

    }
    
    public function accessDenied() {
        redirect(base_url());
    }
    
}