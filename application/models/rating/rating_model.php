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

class Rating_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
        $this->load->library('encrypt');
    }
    
    public function create($entity, $id) {
        $bean = $this->database_model->create('rating');
        $bean->entity = $entity;
        $bean->entityid = '!'.$id;
        $bean->votes = 0;
        $bean->value = 0;
        $bean->last_update = date('Y-m-d H:i:s');
        return $bean;
    }
    
    public function createUserRate(&$rating, $account, $ip, $value) {
        $bean = $this->database_model->create('userrating');
        $bean->rating = $rating;
        $bean->account = $account;
        $bean->ip = $ip;
        $bean->value = $value;
        $rating->votes += 1;
        $rating->value = round(($rating->value + $value) / $rating->votes);
        return $bean;
    }
    
    public function genLink($entity, $id)
    {
        return $this->encode($entity, $id);
    }
    
    public function save($bean)
    {
        $this->database_model->save($bean);
        if (isset($bean->entityid)) $bean->entityid = str_replace ('!', '', $bean->entityid);
        $this->database_model->save($bean);
    }
    
    public function load($entity, $id) {
        return $this->database_model->findOne('rating', ' entity = ? and entityid = ? ', array($entity, $id));
    }
    
    public function loadAll($items, $entity, $account, $ip) {
        $rates = array();
        foreach ($items as $itemid) {
            $rate = array();
            $rate['bean'] = $this->load($entity, $itemid);
            $rate['done'] = 0;
            if (!empty($rate['bean'])) {
                $exists = $this->findUserVote($rate['bean'], $account, $ip);
                if (!empty($exists)) $rate['done'] = 1;
            }
            $rate['code'] = $this->encode($entity, $itemid);
            $rates[$itemid] = $rate;
        }
        return $rates;
    }
    
    public function findUserVote($rating, $account, $ip) {
        return $this->database_model->findOne('userrating', 
                ' rating_id = ? and (account_id = ? or ip = ?)', 
                array($rating->id, $account->id, $ip));
    }
    
    public function encode($entity, $id) {
        return $this->encrypt->encode(implode(' ', array($entity, $id)));
    }
    
    public function decode($string) {
        return explode(' ', $this->encrypt->decode($string));
    }
    
}

?>
