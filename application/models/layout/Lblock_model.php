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

class Lblock_model extends CI_Model {
    
    protected $view;
    protected $links = array();
    protected $scripts = array();
    protected $data = array();
    protected $configuration = array();
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getView() {
        return $this->view;
    }
    
    public function setView($view) {
        $this->view = $view;
    }
    
    public function getLinks() {
        return $this->links;
    }
    
    public function getScripts() {
        return $this->scripts;
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function setData($data) {
        $this->data = $data;
    }
    
    public function addData($name, $item) {
        $this->data[$name] = $item;
    }
    
    public function getConfig() {
        return $this->configuration;
    }
    
    public function setConfig($config) {
        $this->configuration = $config;
    }
    
    public function addConfig($name, $item) {
        $this->configuration[$name] = $item;
    }
    
    public function render($data = array()) {
        if (empty($this->view)) return '';
        $data = array_merge($data, $this->getData());
        return $this->load->view($this->view, $data, TRUE);
    }
    
}

?>
