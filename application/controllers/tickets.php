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

class Tickets extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        // Load ticket model
        $this->load->model('crm/ticket_model');
        
        // Load account model
        $this->load->model('account/account_model');

        // Load language
        $this->lang->load('crm', $this->session->userdata('lang'));
    }
    
    public function index()
    {   
        // Set data
        $data = array(
            'ctrlpath' => 'tickets',
            'form' => array('email' => '', 'ref' => ''),
            'account' => $this->account_model->load('geo')
        );
        
        // output
        $content = $this->load->view('crm/publictickets', $data);
        $this->render($content);

    }
    
    public function read() {
        
        $errors = array();
        $info = array();
        
        // Read ticket
        $email = $this->input->get('email', TRUE);
        $ref = $this->input->get('ref', TRUE);
        
        // Set view data
        $data = array();
        $data['ctrlpath'] = 'tickets';
        $data['form'] = array('email' => '', 'ref' => '');
        $data['account'] = $this->account;
        
        // Check user input
        if (!empty($email) && !empty($ref)) {
            $email = urldecode($email);
            // Load ticket
            $ticket = $this->ticket_model->loadByEmailRef($email, $ref);
            if (empty($ticket)) $errors[] = 'Ticket not found';
            else {
                $info[] = 'The ticket was found.';
                $data['ticket'] = $ticket;
                $ticketlog = $this->ticket_model->loadLog($ticket);
                $data['ticketlog'] = $ticketlog;
            }
            $data['form'] = array('email' => $email, 'ref' => $ref);
            $data['msgs'] = array('errors' => $errors, 'info' => $info);
        }
        
        $data['read'] = true;
        
        // output without layout if is ajax
        $content = $this->load->view('crm/publictickets', $data, TRUE);
        $ldata['pagetitle'] = $this->lang->line('ticket.page.title');
        $this->render($content, $ldata);
    }
    
    
    public function create($layeralias = null, $featureid = null, $pgplacetype = null) {
        
        $errors = array();
        $info = array();
        
        // Create ticket
        $post = $this->input->post(null, TRUE);
        
        // Set view data
        $data['ctrlpath'] = 'tickets';
        $data['create'] = true;
        $data['layeralias'] = $layeralias;
        $data['featureid'] = $featureid;
        $data['pgplacetype'] = $pgplacetype;
        $data['owner'] = $this->account->username;
        
        // set default data
        $assigned = $this->account_model->load('geo');
        if (empty($assigned)) $assigned = $this->account_model->load('admin');

        if (!empty($post)) {

            // Create new ticket
            $ticket = $this->ticket_model->create($this->account->username, $assigned);
            
            try {

                // Validate data
                if (empty($post['email'])) throw new Exception($this->lang->line('ticket.send.emailerror1'));
                if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
                        throw new Exception($this->lang->line('ticket.send.emailerror2'));
                if (empty($post['subject'])) throw new Exception($this->lang->line('ticket.send.subjecterror'));
                if (empty($post['message'])) throw new Exception($this->lang->line('ticket.send.messageerror'));

                // Set new data and save
                $import = array(
                    'owner',
                    'email',
                    'subject',
                    'message',
                    'externalref',
                    'status',
                    'comments'
                );
                if ($this->account->username == 'guest') $import[] = 'email';
                $ticket->import($post, $import);
                if (!empty($post['layeralias']) && !empty($post['featureid'])) {
                    $ticket->layer = $this->database_model->findOne('layer', ' alias = ? ', array($post['layeralias']));
                    $ticket->featureid = $post['featureid'];
                    $ticket->pgplacetype = $post['pgplacetype'];
                }
                $ticket->last_update = date('Y-m-d H:i:s');
                $this->ticket_model->save($ticket);

                // Send email
                $message = $this->load->view('crm/publiccreateticketsuccess', array('ticket' => $ticket), TRUE);

                $this->load->library('email');

                $mail_config['mailtype'] = 'html';
                $this->email->initialize($mail_config);
                $this->email->from($this->config->item('ticket_email_origin'), $this->config->item('ticket_email_name'));
                $this->email->to($ticket->email);

                $this->email->subject($this->config->item('ticket_email_subject'));
                $this->email->message($message);	

                $result = $this->email->send();
                if (!$result) $errors[] = $this->lang->line('ticket.send.deliveryerror');

                file_put_contents($this->config->item('private_data_path').'/mail.log', $this->email->print_debugger());
                
                $info[] = $this->lang->line('ticket.send.ok');
                $data['ticket'] = $ticket;
                $data['success'] = true;
            }
            catch(Exception $e) {
                $errors[] = $e->getMessage();
                $data['newticket'] = $ticket;
            }
        }
        else {
            $ticket = $this->ticket_model->create($this->account->username, $assigned);
            $data['newticket'] = $ticket;
        }
        $data['msgs'] = array('errors' => $errors, 'info' => $info);
        
        // output without layout if is ajax
        $content = $this->load->view('crm/publictickets', $data, TRUE);
        $ldata['pagetitle'] = $this->lang->line('ticket.page.title');
        $this->render($content, $ldata);
    }
    
}