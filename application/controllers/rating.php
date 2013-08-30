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

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rating extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('rating/rating_model');
        
        $this->layout = 'module';
    }
    
    public function index()
    {   
    }
    
    public function vote($value) {
        $code = $this->input->post('code', TRUE);
        $ip = $this->input->ip_address();
        list($entity, $id) = $this->rating_model->decode($code);
        $rating = $this->rating_model->load($entity, $id);
        if (!$rating) $rating = $this->rating_model->create($entity, $id);
        $userrating = $this->rating_model->createUserRate($rating, $this->account, $ip, $value);
        $this->rating_model->save($userrating);
        $this->rating_model->save($rating);
        
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        echo json_encode(array('result' => true), TRUE);
    }
    
}