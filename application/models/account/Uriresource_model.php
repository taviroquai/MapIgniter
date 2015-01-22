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

class Uriresource_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
    }
    
    public function create($pattern)
    {
        $bean = $this->database_model->create('uriresource');
        $bean->pattern = $pattern;  
        return $bean;
    }
    
    public function loadAll() {
        return $this->database_model->find('uriresource', ' true ');
    }
    
    public function loadById($id) {
        return $this->database_model->load('uriresource', $id);
    }
    
    public function delete($ids) {
        $this->database_model->delete('uriresource', $ids);
    }
    
    public function save(&$bean)
    {   
        return $this->database_model->save($bean);
    }
    
}

?>
