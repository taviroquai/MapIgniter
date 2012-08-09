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

require_once APPPATH.'controllers/admin/adminticket.php';

class Manageticket extends Adminticket {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('crm/ticket_model');
        $this->load->model('account/account_model');
        
        $this->layout = 'registered';
        $this->ctrlpath = 'user/'.$this->router->fetch_class();
        $this->listview = 'user/crm/usertickets';
    }
    
    /**
     * Action index
     * Display a list of tickets
     * TODO: Pagination
     */
    public function index()
    {   
        // Load all tickets
        // TODO: Pagination
        $tickets = $this->ticket_model->loadByAccountOpen($this->account);
        $ticket = $this->ticket_model->create($this->account->username, $this->account);
        
        // Load main content
        $data = array(
            'items' => $tickets,
            'ticket' => $ticket,
            'accounts' => $this->account_model->loadAll(),
            'owner' => $this->account->username,
            'statusopts' => $this->ticket_model->getAllStatus(),
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        
        // Add rating items
        if (!empty($tickets)) {
            foreach ($tickets as $item) {
                $ratingitems[] = $item->id;
            }
            $data['rating'] = 
            $this->rating_model->loadAll($ratingitems, 'ticket', $this->account, $this->input->ip_address());
        }
        
        $content = $this->load->view($this->listview, $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
}