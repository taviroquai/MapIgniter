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

class Adminticket extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('crm/ticket_model');
        $this->load->model('account/account_model');
        $this->load->model('rating/rating_model');
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        $this->listview = 'admin/crm/admintickets';
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
        $tickets = $this->ticket_model->loadAll();
        $ticket = $ticket = $this->ticket_model->create($this->account->username, $this->account);
        
        // Load main content
        $data = array(
            'items' => $tickets,
            'ticket' => $ticket,
            'accounts' => $this->account_model->loadAll(),
            'owner' => $this->account->username,
            'statusopts' => $this->ticket_model->getAllStatus(),
            'ctrlpath' => $this->ctrlpath,
            'action' => '/save/new');
        $content = $this->load->view($this->listview, $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action edit
     * Opens a form for ticket edition
     * @param string $id 
     */
    public function edit($id)
    {   
        try {
            // Load ticket
            $ticket = $this->ticket_model->load($id);
            if (!$ticket) throw new Exception('Ticket not found!');
            
            // Load main content
            $data = array(
                'ticket' => $ticket,
                'ticketlog' => $this->ticket_model->loadLog($ticket),
                'accounts' => $this->account_model->loadAll(),
                'owner' => $ticket->owner,
                'statusopts' => $this->ticket_model->getAllStatus(),
                'groups' => $this->group_model->loadAll(),
                'ctrlpath' => $this->ctrlpath);
            $data['action'] = ($id == 'new') ? '/save/new' : '/save/'.$ticket->id;
            $content = $this->load->view('admin/crm/admineditticket', $data, TRUE);
        }
        catch (Exception $e) {
            $content = "<p>{$e->getMessage()}</p>";
        }
        
        // Render
        $this->render($content);
    }
    
    /**
     * Action save
     * Saves the new ticket follow up data 
     * @param string $id 
     */
    public function save($id)
    {
        $errors = array();
        $info = array();
        
        try {
            // load post data
            $post = $this->input->post(NULL, TRUE);
            
            // Create new account
            if ($id === 'new') {
                $ticket = $this->ticket_model->create($this->account->username, $this->account);
            }
            // Load existing ticket
            else {
                $ticket = $this->ticket_model->load($id);
                if (!$ticket) throw new Exception('Ticket not found!');
            }

            // Validate data
            if (empty($post['subject'])) throw new Exception('The subject is required.');
            if (empty($post['message'])) throw new Exception('The message is required.');
            
            // Save ticket log
            if ($id != 'new') $this->ticket_model->saveLog($ticket, $post['comments']);
            
            // Set new data and save
            $import = array(
                'owner',
                'subject',
                'message',
                'externalref',
                'status',
                'comments'
            );
            if ($this->account->username == 'guest') $import[] = 'email';
            $ticket->import($post, $import);
            $ticket->account = $this->account_model->loadById($post['assigned']);
            $ticket->last_update = date('Y-m-d H:i:s');
            $this->ticket_model->save($ticket);
            
            $info[] = 'The ticket was saved';
            
        }
        catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'ticket' => $ticket,
            'ticketlog' => $this->ticket_model->loadLog($ticket),
            'accounts' => $this->account_model->loadAll(),
            'owner' => $ticket->owner,
            'statusopts' => $this->ticket_model->getAllStatus(),
            'action' => '/save/'.$ticket->id,
            'ctrlpath' => $this->ctrlpath);
        $content = $this->load->view('admin/crm/admineditticket', $data, TRUE);
        $this->render($content);
    }
    
    /**
     * Action delete
     * Deleted the selected accounts
     */
    public function delete()
    {
        $selected = $this->input->post('selected');
        if (!empty($selected)) $this->ticket_model->delete($selected);
        if (!$this->input->is_ajax_request())
            redirect(base_url().$this->ctrlpath);
    }
    
}