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

class Publicmap extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        // Set content
        $content = $this->load->view('news', NULL, true);
        $result = $this->database_model->find('controller', 'path = ?', array('publicmap'));
        if (!empty($result)) {
            $ctl = reset($result);
            if (!empty($ctl->layout->content)) $content = $ctl->layout->content;
        }
        
        // Load layout
        $this->render($content);
    }
    
}