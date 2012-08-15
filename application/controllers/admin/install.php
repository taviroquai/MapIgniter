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

class Install extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
    }
    
    public function index()
    {
        $errors = array();
        $info = array();
        $installdb = $this->input->post('installdb');
        
        try {
            
            // Check MapServer
            $cmd = $this->config->item('mapserver_path').' -v ';
            $info[] = "Detecting MapServer with: $cmd";
            exec($cmd, $msoutput);
            $info[] = implode('<br />', $msoutput);
            $regex = preg_match('/MapServer version \d+\.\d+/i', implode(" ", $msoutput), $matches);
            if (!$regex) throw new Exception('MapServer was not detected.');
            
            // Check if can be called by configured url
            $info[] = "Detecting MapServer at url: ".$this->config->item('mapserver_cgi');
            $mapserver_cgi = trim(file_get_contents($this->config->item('mapserver_cgi')));
            $info[] = $mapserver_cgi;
            if ($mapserver_cgi !== 'No query information to decode. QUERY_STRING is set, but empty.')
                throw new Exception('MapServer is not responding at url: '.$this->config->item('mapserver_cgi'));
            
            // Check PostgreSQL
            $cmd = 'psql --version';
            $info[] = "Detecting PostgreSQL with: $cmd";
            exec($cmd, $pgoutput);
            $info[] = implode('<br />', $pgoutput);
            $regex = preg_match('/psql \(PostgreSQL\) \d+\.\d+\.\d+/i', implode(" ", $pgoutput), $matches);
            if (!$regex) throw new Exception('PostgreSQL was not detected.');
            
            // Check php5-psql
            $info[] = "Detecting php5-pgsql with extension_loaded()";
            if (!extension_loaded('pgsql')) throw new Exception('php5-pgsql extension was not detected.');
            $info[] = "php5-pgsql is loaded";
            
            // Check php5-curl
            $info[] = "Detecting php5-curl with extension_loaded()";
            if (!extension_loaded('curl')) throw new Exception('php5-curl extension was not detected.');
            $info[] = "php5-curl is loaded";
            
            // Check php5-gd
            $info[] = "Detecting php5-gd with extension_loaded()";
            if (!extension_loaded('gd')) throw new Exception('php5-gd extension was not detected.');
            $info[] = "php5-gd is loaded";
            
            // Check php-apc
            $info[] = "Detecting php-apc with extension_loaded()";
            if (!extension_loaded('apc')) throw new Exception('php-apc extension was not detected.');
            $info[] = "php-apc is loaded";
            
            // Check if private data folder is writeable
            $private_data_path = $this->config->item('private_data_path');
            $info[] = "Checking private data folder: $private_data_path ...";
            $private_folder = is_writable($private_data_path);
            if (!$private_folder) {
                $info[] = 'The private data folder cannot be written by user www-data.';
                throw new Exception ('The private data folder ('.$private_data_path.') cannot be written.');
            }
            
            // Check if public data folder is writeable
            $data_path = $this->config->item('public_data_path');
            $info[] = "Checking public data folder: $data_path ...";
            $writeable = is_writable($data_path);
            if (!$writeable) {
                $info[] = 'The public data folder cannot be written by user www-data.';
                throw new Exception ('The public data folder ('.$data_path.') cannot be written.');
            }
            
            // Check database
            $info[] = 'Checking database...';
            $tables = $this->database_model->checkSchema();
            if (empty($tables)) $info[] = 'Database does exists but is empty.';
            else $info[] = 'Database has '.count($tables).' tables.';
            
            if ($installdb) {
                // Install database
                $info[] = 'Installing database...';
                $this->database_model->install();
                $info[] = '... database and demo data were installed.';
            }
            
            $view = 'admin/install_success';
        }
        catch(Exception $e) {
            $errors[] = 'There was at least 1 error when checking system requirements.';
            $errors[] = $e->getMessage();
            $view = 'admin/install_failed';
        }
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'installdb' => empty($installdb)
        );
        $content = $this->load->view($view, $data, TRUE);
        $this->load->view('layout/default', array('content' => $content));
    }
    
}