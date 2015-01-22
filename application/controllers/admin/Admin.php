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
 * @link		http://marcoafonso.com/mapigniter/doku.php
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     Controller
 * @category    Administration
 */
class Admin extends MY_Controller {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        $this->layout = 'admin';
        $this->load->model('stats/stats_model');
    }
    
    /**
     * Administration Dashboard
     * Specific Business Model content should go here
     */
    public function index()
    {
        // Load main content
        $graphs = $this->stats_model->adminStats();
        $content = $this->load->view('admin/admin_info', array('graphs' => $graphs), TRUE);
        
        // Render content
        $this->render($content);
        
    }
    
}