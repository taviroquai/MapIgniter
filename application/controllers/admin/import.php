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

class Import extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->layout = 'admin';
        $this->ctrlpath = 'admin/'.$this->router->fetch_class();
        
        $this->load->model('map_model');
    }
    
    /**
     * Action index
     * Display a list user group maps
     */
    public function index()
    {
        // Load upload library
        $this->load->helper(array('form'));
        
        // Load Postgis model
        $this->load->model('database/postgis_model');
        
        // Check for post
        $post = $this->input->post();
        if (!empty($post)) {
            
            // Init error var
            $error = null;
            
            // Configure upload
            $config['upload_path'] = $this->config->item('private_data_path');
            $config['allowed_types'] = 'zip';
            $config['max_size'] = '1000';
            $config['overwrite'] = true;

            $this->load->library('upload', $config);
            $this->load->helper('form');

            // Do the upload
            if ( ! $this->upload->do_upload())
            {
                $error = $this->upload->display_errors('', '');
            }
            else
            {
                // Get upload successful data
                $upload_info = $this->upload->data();
                $zipfile = $upload_info['full_path'];
                try {
                    
                    // Set tablename: new or existing will overwrite
                    $tablename = $post['new_pgplacetype'] == 'new_pgplacetype' ? 
                        $post['new_pgplacetype'] : $post['pgplacetype'];

                    // Get postgis import results
                    $import_results = 
                        $this->postgis_model->importZip(
                            $zipfile, 
                            $tablename, 
                            $post['srid']);
                    
                    // Show import results
                    $content = $this->load->view('admin/import/result', $import_results, TRUE);
                    $this->render($content);
                    return true;
                }
                catch (Exception $e) {
                    $error = $e->getMessage();
                }
                
                // Clean zip file
                unlink($zipfile);
            }
        }

        // Load main content Filter by account group
        $items = $this->map_model->loadAllByAccount($this->account);
        $data = array(
            'msgs'      => array('info' => array(), 'errors' => array()),
            'items'     => $items,
            'map'       => $this->map_model->create(),
            'tables'    => $this->postgis_model->loadAllTables(),
            'srid_list' => $this->postgis_model->loadAllSRID(),
            'ctrlpath'  => $this->ctrlpath,
            'action'    => '');
        
        // Add upload information to view
        if (!empty($error)) $data['msgs']['errors'][] = $error;
            
        // Load view
        $content = $this->load->view('admin/import/general', $data, TRUE);
        
        // Render
        $this->render($content);
    }
    
}