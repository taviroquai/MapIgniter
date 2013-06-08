<?php 

/**
 * MapIgniter
 *
 * An open source GeoCMS application
 *
 * @package		MapIgniter
 * @author		Marco Afonso
 * @copyright	Copyright (c) 2012-2013, Marco Afonso
 * @license		dual license, one of two: Apache v2 or GPL
 * @link		http://marcoafonso.com/miwiki/doku.php
 * @since		Version 1.1
 * @filesource
 */

// ------------------------------------------------------------------------

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends CI_Controller {

    private $app_path;
    private $app_url;
    
    public function __construct() {
        parent::__construct();
        
        $this->app_path = realpath(APPPATH.'../../application');
        $this->app_url = base_url('../');
    }
    
    public function index()
    {
        $errors = array();
        $info = array();
        $post = $this->input->post();
        $install = $this->input->post('install');
        $this->load->config('mapigniter');
        $defaults = array(
            'private_data_path' => realpath('../'.$this->config->item('private_data_path')).'/',
            'public_data_path'  => realpath('../'.$this->config->item('public_data_path')).'/',
            'cache_file_path'   => realpath('../'.$this->config->item('cache_file_path')).'/',
            'mapserver_path'    => $this->config->item('mapserver_path'),
            'mapserver_cgi'     => $this->config->item('mapserver_cgi'),
            'psql_path'         => $this->config->item('psql_path'),
            'shp2pgsql_path'    => $this->config->item('shp2pgsql_path'),
            
            'ticket_email_origin'  => $this->config->item('ticket_email_origin'),
            'ticket_email_name'    => $this->config->item('ticket_email_name'),
            'ticket_email_subject' => $this->config->item('ticket_email_subject'),
            'cache_expire'      => $this->config->item('cache_expire'),
            'cache_on'      => $this->config->item('cache_on'),
            
            'db_default_dbdriver'   => 'postgre',
            'db_default_database'   => 'mapigniter',
            'db_default_hostname'   => 'localhost',
            'db_default_username'   => 'mapigniter',
            'db_default_password'   => 'postgres',
            
            'db_userdata_dbdriver'   => 'postgre',
            'db_userdata_database'   => 'mapigniterdata',
            'db_userdata_hostname'   => 'localhost',
            'db_userdata_username'   => 'mapigniter',
            'db_userdata_password'   => 'postgres',
        );
        
        // Setup form defaults
        foreach ($defaults as $key => $value) {
            if (empty($post[$key])) $post[$key] = $value;
        }
        
        try {
            
            // Check MapServer
            $cmd = $post['mapserver_path'].' -v ';
            $info[] = "Detecting MapServer with: $cmd";
            exec($cmd, $msoutput);
            //$info[] = implode('<br />', $msoutput);
            $regex = preg_match('/MapServer version \d+\.\d+/i', implode(" ", $msoutput), $matches);
            if (!$regex) throw new Exception('MapServer was not detected.');
            
            // Check if can be called by configured url
            $info[] = "Detecting MapServer at url: ".$post['mapserver_cgi'];
            $mapserver_cgi = trim(file_get_contents($post['mapserver_cgi']));
            //$info[] = $mapserver_cgi;
            if ($mapserver_cgi !== 'No query information to decode. QUERY_STRING is set, but empty.')
                throw new Exception('MapServer is not responding at url: '.$post['mapserver_cgi']);
            
            // Check PostgreSQL
            $cmd = $post['psql_path'].' --version';
            $info[] = "Detecting PostgreSQL with: $cmd";
            exec($cmd, $pgoutput);
            //$info[] = implode('<br />', $pgoutput);
            $regex = preg_match('/psql \(PostgreSQL\) \d+\.\d+\.\d+/i', implode(" ", $pgoutput), $matches);
            if (!$regex) throw new Exception('PostgreSQL was not detected.');
            
            // Check shp2pgsql tool
            $cmd = $post['shp2pgsql_path'];
            $info[] = "Detecting shp2pgsql with: $cmd";
            exec($cmd, $shp2pgoutput);
            //$info[] = htmlentities(implode('<br />', $shp2pgoutput));
            $regex = preg_match('/RELEASE\: \d+\.\d+/i', implode(" ", $shp2pgoutput), $matches);
            if (!$regex) throw new Exception('shp2pgsql was not detected.');
            
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
            
            // Check if private data folder is writeable
            $private_data_path = $post['private_data_path'];
            $info[] = "Checking private data folder: $private_data_path ...";
            $private_folder = is_writable($private_data_path);
            if (!$private_folder) {
                $info[] = 'The private data folder cannot be written by user www-data.';
                throw new Exception ('The private data folder ('.$private_data_path.') cannot be written.');
            }
            
            // Check if public data folder is writeable
            $data_path = $post['public_data_path'];
            $info[] = "Checking public data folder: $data_path ...";
            $writeable = is_writable($data_path);
            if (!$writeable) {
                $info[] = 'The public data folder cannot be written by user www-data.';
                throw new Exception ('The public data folder ('.$data_path.') cannot be written.');
            }
            
            // Check if configuration directory is writeable
            $info[] = 'Checking for configuration directory...';
            $dir = dirname($this->app_path.'/config/mapigniter.php');
            if (!is_writable($dir)) {
                throw new Exception('Cannot create configuration files at '.$dir);
            }
            
            // check apache rewrite module
            $info[] = 'Checking for apache rewrite module with apache_get_modules() function...';
            $apache_modules = apache_get_modules();
            if (!in_array('mod_rewrite', $apache_modules)) {
                throw new Exception('Apache module rewrite was not detected');
            }
            
            // Check if mod_rewrite is active for application folder
            $info[] = 'Checking if mod_rewrite is enabled for application folder...';
            // Use test controller to test
            $test_url = str_replace('/install/..', '/test', $this->app_url);
            $expected_result = 'test'; 
            $result = trim(@file_get_contents($test_url));
            if ($result !== $expected_result) {
                throw new Exception('Apache mod_rewrite is not working for application folder. Please change Apache configuration.');
            }
            
            // Check database
            $info[] = 'Checking database...';
            $this->load->model('database_model');
            // Setup DB connections
            R::addDatabase('default', "pgsql:host={$post['db_default_hostname']};dbname={$post['db_default_database']}", $post['db_default_username'], $post['db_default_password']);
            R::selectDatabase('default');
            $tables = $this->database_model->checkSchema();
            if (empty($tables)) $info[] = 'Application database exists.';
            else $info[] = 'Application database has '.count($tables).' tables.';
            
            // Setup DB connection
            R::addDatabase('userdata', "pgsql:host={$post['db_userdata_hostname']};dbname={$post['db_userdata_database']}", $post['db_userdata_username'], $post['db_userdata_password']);
            R::selectDatabase('userdata');
            $tables = $this->database_model->checkSchema();
            if (empty($tables)) $info[] = 'User data database does exists.';
            else $info[] = 'User data has '.count($tables).' tables.';
            
            
            // Proceed to install if user ordered
            if ($install) {
                
                // Create configuration files
                $this->saveConfigFiles($post);
                foreach ($post as $k => $v) $this->config->set_item($k, $v);
                // Force the Session class to recapture global settings by calling it's constructor
                //$this->session->CI_Session();
                
                // Install database
                $info[] = 'Installing database...';
                R::selectDatabase('default');
                $this->database_model->install();
                $info[] = '... database and demo data were installed.';
            }
            
            $config_ok = true;
        }
        catch(Exception $e) {
            $errors[] = 'There was at least 1 error when checking system requirements.';
            $errors[] = $e->getMessage();
            $config_ok = false;
        }
        
        // Prepare output data
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'post' => $post, 
            'defaults' => $defaults,
            'config_ok' => $config_ok,
            'install' => !empty($install),
            'app_url' => $this->app_url
        );
        $content = $this->load->view('install', $data, TRUE);
        $this->load->view('layout/default', array('content' => $content));
    }
    
    public function saveConfigFiles($data) {
        
        // Generate mapigniter config file
        $config_file = file_get_contents(APPPATH.'/views/mapigniter.tmpl.php');
        foreach ($data as $k => $v) {
            $config_file = str_replace('{{'.$k.'}}', $v, $config_file);
        }
        file_put_contents($this->app_path.'/config/mapigniter.php', $config_file);
        
        // Generate database config file
        $config_file = file_get_contents(APPPATH.'/views/database.tmpl.php');
        foreach ($data as $k => $v) {
            $config_file = str_replace('{{'.$k.'}}', $v, $config_file);
        }
        file_put_contents($this->app_path.'/config/database.php', $config_file);
    }
    
}
