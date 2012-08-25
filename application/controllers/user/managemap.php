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

require_once APPPATH.'controllers/admin/adminmap.php';

class Managemap extends Adminmap {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'registered';
        $this->ctrlpath = 'user/'.$this->router->fetch_class();
        $this->msmapctrlpath = 'user/managemsmap';
        $this->olmapctrlpath = 'user/manageolmap';
    }
    
    /**
     * Action index
     * Display a list user group maps
     */
    public function index()
    {
        // Load Postgis model
        $this->load->model('database/postgis_model');

        // Load main content Filter by account group
        $items = $this->map_model->loadAllByAccount($this->account);
        $data = array(
            'items' => $items,
            'map' => $this->map_model->create(),
            'tables' => $this->postgis_model->loadAllTables(),
            'srid_list' => $this->postgis_model->loadAllSRID(),
            'geom_types' => $this->postgis_model->loadAllGeomTypes(),
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        
        // Add rating items
        if (!empty($items)) {
            foreach ($items as $item) $ratingitems[] = $item->id;
            $data['rating'] = 
            $this->rating_model->loadAll($ratingitems, 'map', $this->account, $this->input->ip_address());
        }
        $content = $this->load->view('user/map/ownermap', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
}