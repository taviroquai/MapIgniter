<?php 

/**
 * MapIgniter
 *
 * An open source GeoCMS application
 *
 * @package		MapIgniter
 * @author		Marco Afonso
 * @copyright	Copyright (c) 2012-2013-2013, Marco Afonso
 * @license		dual license, one of two: Apache v2 or GPL
 * @link		http://mapigniter.com/
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
        $config_ok = false;
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
            $info['mapserver_path'] = "Detecting MapServer with: $cmd";
            exec($cmd, $msoutput);
            //$info[] = implode('<br />', $msoutput);
            $regex = preg_match('/MapServer version \d+\.\d+/i', implode(" ", $msoutput), $matches);
            if (!$regex) $errors['mapserver_path'] = 'MapServer was not detected.';
            
            // Check if can be called by configured url
            $info['mapserver_cgi'] = "Detecting MapServer at url: ".$post['mapserver_cgi'];
            $mapserver_cgi = trim(@file_get_contents($post['mapserver_cgi']));
            //$info[] = $mapserver_cgi;
            if ($mapserver_cgi !== 'No query information to decode. QUERY_STRING is set, but empty.') {
                $errors['mapserver_cgi'] = 'MapServer is not responding at url: '.$post['mapserver_cgi'];
            }
            
            // Check PostgreSQL
            if (strstr($post['psql_path'], '.exe')) $cmd = '"'.$post['psql_path'].'" --version';
            else $cmd = $post['psql_path'].' --version';
            $info['postgresql'] = "Detecting PostgreSQL with: $cmd";
            exec($cmd, $pgoutput);
            //$info[] = implode('<br />', $pgoutput);
            $regex = preg_match('/psql \(PostgreSQL\) \d+\.\d+/i', implode(" ", $pgoutput), $matches);
            if (!$regex) $errors['postgresql'] = 'PostgreSQL was not detected.';
            
            // Check shp2pgsql tool
            if (strstr($post['shp2pgsql_path'], '.exe')) $cmd = '"'.$post['shp2pgsql_path'].'"';
            else $cmd = $post['shp2pgsql_path'];
            $info['shp2pgsql_path'] = "Detecting shp2pgsql with: $cmd";
            exec($cmd, $shp2pgoutput);
            //$info[] = htmlentities(implode('<br />', $shp2pgoutput));
            $regex = preg_match('/RELEASE\: \d+\.\d+/i', implode(" ", $shp2pgoutput), $matches);
            if (!$regex) $errors['shp2pgsql_path'] = 'shp2pgsql was not detected.';
            
            // Check php5-psql
            $info['php5_pgsql'] = "Detecting php5-pgsql with extension_loaded()";
            if (!extension_loaded('pgsql')) $errors['php5_pgsql'] = 'php5-pgsql extension was not detected.';
            
            // Check php5-curl
            $info['php5_curl'] = "Detecting php5-curl with extension_loaded()";
            if (!extension_loaded('curl')) $errors['php5_curl'] = 'php5-curl extension was not detected.';
            
            // Check php5-gd
            $info['php5_gd'] = "Detecting php5-gd with extension_loaded()";
            if (!extension_loaded('gd')) $errors['php5_gd'] = 'php5-gd extension was not detected.';
            
            // Check php5-mcrypt
            $info['php5_mcrypt'] = "Detecting php5-mcrypt with extension_loaded()";
            if (!extension_loaded('mcrypt')) $errors['php5_mcrypt'] = 'php5-mcrypt extension was not detected.';
            
            // Check if private data folder is writeable
            $private_data_path = $post['private_data_path'];
            $info['private_data_path'] = "Checking private data folder: $private_data_path ...";
            $private_folder = is_writable($private_data_path);
            if (!$private_folder) {
                $errors['private_data_path'] = 'The private data folder ('.$private_data_path.') cannot be written.';
            }
            
            // Check if public data folder is writeable
            $data_path = $post['public_data_path'];
            $info['public_data_path'] = "Checking public data folder: $data_path ...";
            $writeable = is_writable($data_path);
            if (!$writeable) {
                $errors['public_data_path'] = 'The public data folder ('.$data_path.') cannot be written.';
            }
            
            // Check if configuration directory is writeable
            $info['config_path'] = 'Checking for configuration directory...';
            $dir = dirname($this->app_path.'/config/mapigniter.php');
            if (!is_writable($dir)) {
                $errors['config_path'] = 'Cannot create configuration files at '.$dir;
            }
            
            // check apache rewrite module
            $info['apache_mod_rewrite'] = 'Checking for apache rewrite module with apache_get_modules()';
            if (function_exists('apache_get_modules')) $apache_modules = apache_get_modules();
            else $apache_modules = $this->apache_get_modules();
            if (!in_array('mod_rewrite', $apache_modules)) {
                $errors['apache_mod_rewrite'] = 'Apache module rewrite was not detected';
            }
            
            // Check if mod_rewrite is active for application folder
            $info['htaccess_override'] = 'Checking if mod_rewrite is enabled for application folder...';
            // Use test controller to test
            $test_url = str_replace('/install/..', '/test', $this->app_url);
            $expected_result = 'test'; 
            $result = trim(@file_get_contents($test_url));
            if ($result !== $expected_result) {
                $errors['htaccess_override'] = 'Apache mod_rewrite is not working for application folder. Please change Apache configuration.';
            }
            
            // Check database
            $info['db_default_connect'] = 'Checking application database...';
            $this->load->model('database_model');
            // Setup DB connections
            R::addDatabase('default', "pgsql:host={$post['db_default_hostname']};dbname={$post['db_default_database']}", $post['db_default_username'], $post['db_default_password']);
            R::selectDatabase('default');
            try {
                $tables = $this->database_model->checkSchema();
            } catch (Exception $e) {
                $errors['db_default_connect'] = $e->getMessage();
            }
            
            // Setup DB connection
            $info['db_userdata_connect'] = 'Checking user data database...';
            R::addDatabase('userdata', "pgsql:host={$post['db_userdata_hostname']};dbname={$post['db_userdata_database']}", $post['db_userdata_username'], $post['db_userdata_password']);
            R::selectDatabase('userdata');
            try {
                $tables = $this->database_model->checkSchema();
            } catch (Exception $e) {
                $errors['db_userdata_connect'] = $e->getMessage();
            }
            
            // Check postgis on data database
            $info['db_userdata_postgis'] = 'Checking postgis on user data database...';
            R::selectDatabase('userdata');
            try {
                $result = R::getRow('SELECT postgis_full_version()');
            } catch (Exception $e) {
                $errors['db_userdata_postgis'] = 'Postgis was not found in "user data" database. Please add plpgsql, postgis functions and spatial reference table to the database.';
            }            
            
            // Proceed to install if user ordered
            if ($install) {
                
                // Create configuration files
                $exists = file_exists($this->app_path.'/config/mapigniter.php');
                if (!$exists) $this->saveConfigFiles($post);
                foreach ($post as $k => $v) $this->config->set_item($k, $v);
                
                // Install database
                R::selectDatabase('default');
                $current_version = $this->database_model->getVersion();
                //var_dump($current_version); die();
                $upgrade = 'upgrade_'.$current_version.'_'.$this->config->item('_version');
                if (method_exists($this, $upgrade)) {
                    $this->$upgrade();
                }
                if ($current_version === false) $this->database_model->install();
                else $this->database_model->setVersion($this->config->item('_version'));
                
                $this->load->model('mapserver/mapserver_model');
                $this->mapserver_model->updateMapfile(1);
            }
            
            if (empty($errors)) $config_ok = true;
        }
        catch(Exception $e) {
            $errors['system'] = 'There was at least 1 error when checking system requirements.';
            $errors['system'] = $e->getMessage();
        }
        
        // Prepare output data
        $data = array(
            'msgs' => array('errors' => $errors, 'info' => $info),
            'post' => $post, 
            'defaults' => $defaults,
            'config_ok' => $config_ok,
            'install' => !empty($install),
            'app_url' => $this->app_url,
            'current_version' => !empty($current_version) ? $current_version : false
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
    
    private function apache_get_modules() {
        // Fallback for ms4w PHP under cgi
        // TODO: needs more work.
        // For now consider apache config at /ms4w/Apache/conf/http.conf
        $modules = array();
        $apache_config = '/ms4w/Apache/conf/httpd.conf';
        if (!file_exists($apache_config)) die('Unable to find Apache configuration at '.$apache_config);
        if (!is_readable($apache_config)) die('Unable to read Apache configuration at '.$apache_config);
        $fcont = file($apache_config);
        if (!is_array($fcont)) die('Unable to parse Apache configuration file at '.$apache_config);			  
        foreach ($fcont as $line) {
            if (preg_match ("/^LoadModule\s*(\S*)\s*(\S*)/i", $line, $match)) {
                $name = basename($match[2]); // remove path
                $name = substr($name, 0, (strlen ($name)) - (strlen (strrchr($name, '.')))); // remove extension
                $modules[] = $name;
            }
        }
        return $modules;
    }
    
}
