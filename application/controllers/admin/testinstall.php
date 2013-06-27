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

class Testinstall extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        
        $this->load->model('map_model');
    }
    
    /**
     * Action index (only allowed to admins)
     * Runs database install for development purposes
     */
    public function index()
    {
        
        // Load Postgis model
        $this->load->model('database/postgis_model');
        
        // Run install
        $data = array(
            'msgs' => array('info' => array(), 'errors' => array())
        );
        
        try {
            $this->database_model->install();
        }
        catch (Exception $e) {
            $data['msgs']['error'][] = $e->getMessage();
        }
        
        // Load view
        $content = $this->load->view('admin/testinstall', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
}