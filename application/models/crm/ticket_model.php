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

class Ticket_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
    }
    
    public function create($owner, $assigned, $subject = 'Subject', $message = 'Description', $status = 'uncomfirmed')
    {
        $bean = $this->database_model->create('ticket');
        $bean->owner = $owner;
        $bean->email = '';
        $bean->account = $assigned;
        $bean->subject = $subject;
        $bean->message = $message;
        $bean->map = '';
        $bean->layer = '';
        $bean->feature = '';
        $bean->externalref = $this->genReference(6);
        $bean->comments = '';
        $bean->status = $status;
        $bean->last_update = date('Y-m-d H:i:s');
        
        return $bean;
    }
    
    public function saveLog($ticket)
    {
        $bean = $this->database_model->create('ticketlog');
        $bean->ticket = $ticket;
        $bean->owner = $ticket->owner;
        $bean->account = $ticket->account;
        $bean->email = $ticket->email;
        $bean->subject = $ticket->subject;
        $bean->message = $ticket->message;
        $bean->map = $ticket->map;
        $bean->layer = $ticket->layer;
        $bean->feature = $ticket->feature;
        $bean->externalref = $ticket->externalref;
        $bean->status = $ticket->status;
        $bean->comments = $ticket->comments;
        $bean->last_update = $ticket->last_update;
        $this->database_model->save($bean);
        return $bean;
    }
    
    public function save(&$bean)
    {   
        return $this->database_model->save($bean);
    }
    
    public function loadByAccountOpen($account) {
        $values = array($account->id, 'unconfirmed', 'new', 'delegated', 'reopen');
        return $this->database_model->find('ticket', ' account_id = ? and status IN (?,?,?,?)', $values);
    }
    
    public function load($id) {
        return $this->database_model->load('ticket', $id);
    }
    
    public function loadAll() {
        return $this->database_model->find('ticket', ' true ');
    }
    
    public function loadByEmailRef($email, $ref) {
        return $this->database_model->findOne('ticket', ' email = ? and externalref = ? ', array($email, $ref));
    }
    
    public function loadLog($ticket) {
        return $this->database_model->find('ticketlog', ' ticket_id = ? order by last_update ', array($ticket->id));
    }
    
    public function delete($ids) {
        foreach ($ids as $id) {
            $ticket = $this->database_model->load('ticket', $id);
            $logs = $this->database_model->find('ticketlog', ' ticket_id = ? ', array($id));
            if (!empty($logs)) {
                foreach ($logs as $log) $logsids[] = $log->id;
                $this->database_model->delete('ticketlog', $logsids);
            }
        }
        $this->database_model->delete('ticket', $ids);
    }
    
    public function getAllStatus() {
        return array(
            'unconfirmed' => 'Unconfirmed...',
            'new' => 'Confirmed - New',
            'delegated' => 'Delegated',
            'reopened' => 'Reopen',
            'resolvido' => 'Resolved',
            'filed' => 'Filed',
            'invalid' => 'Invalid - It is not a problem',
            'pendente' => 'Waiting third party...',
            'duplicate' => 'Duplicate'
            );
    }
    
    public function genReference($len) {
        $start = microtime(true);
        $allowed = array('aeiou', 'BCDFGHJKLMNPQRSTVWXZ', '123456789');
        $ref = '';
        do {
            while (strlen($ref) < $len) {
                $ref .= $allowed[1][rand(0, strlen($allowed[1])-1)];
                $ref .= $allowed[0][rand(0, strlen($allowed[0])-1)];
                $ref .= strtolower($allowed[1][rand(0, strlen($allowed[1])-1)]);
            }
            $ref .= strtolower($allowed[2][rand(0, strlen($allowed[2])-1)]);
            $ref .= strtolower($allowed[2][rand(0, strlen($allowed[2])-1)]);
        }
        while ($this->database_model->find('ticket', ' externalref = ? ', array($ref)));
        return $ref;
    }
    
}

?>
