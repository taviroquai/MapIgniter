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

require_once APPPATH.'controllers/admin/adminlayer.php';

class Managelayer extends Adminlayer {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'registered';
        $this->ctrlpath = 'user/'.$this->router->fetch_class();
        $this->pglayerctrlpath = 'user/managepglayer';
        $this->mslayerctrlpath = 'user/managemslayer';
        $this->ollayerctrlpath = 'user/manageollayer';
        $this->listview = 'user/layer/ownerlayer';
        $this->editview = 'user/layer/ownereditlayer';
    }
    
    /**
     * Action index
     * Display a list of own layers
     */
    public function index()
    {   
        // Filter by account group
        $account = $this->account_model->load($this->session->userdata('username'));
        
        // Load all layers
        // TODO: Pagination
        $items = $this->layer_model->loadAllByAccount($account);
        
        // Temp layer
        $layer = $this->layer_model->create();
        
        // Load main content
        $data = array('items' => $items, 
            'layer' => $layer,
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        
        // Add rating items
        if (!empty($items)) {
            foreach ($items as $item) $ratingitems[] = $item->id;
            $data['rating'] = 
            $this->rating_model->loadAll($ratingitems, 'layer', $this->account, $this->input->ip_address());
        }
        $content = $this->load->view($this->listview, $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
}