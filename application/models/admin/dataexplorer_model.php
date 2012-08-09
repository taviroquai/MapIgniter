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

class Dataexplorer_model extends CI_Model {
    
    protected $private_base;
    protected $public_base;
    protected $current;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
        $this->private_base = $this->config->item('private_data_path');
        $this->public_base = $this->config->item('public_data_path');
    }
    
    public function listdir($dir, $security = 'private')
    {
        
        // Load main content
        $this->current = $dir;
        $currentdir = $this->getBase($security).$dir;
        $current = @opendir($currentdir);
        if (!$current) throw new Exception('Diretorio inválido!');

        // get each entry
        while($entryName = readdir($current)) {
            $entry = array('name' => $entryName, 'sys' => null);
            $bean = $this->database_model->findOne('datapath', ' path = ? and security = ? ', array($dir.$entryName, $security));
            if ($bean) $entry['sys'] = $bean;
            $dirArray[] = $entry;
        }

        // close directory
        closedir($current);

        // sort 'em
        sort($dirArray);
        return $dirArray;

    }
    
    public function getCurrent() {
        return $this->current;
    }
    
    public function getBase($security = 'private') {
        if ($security == 'public') return $this->public_base;
        return $this->private_base;
    }
    
    public function dl($dir, $file, $security = 'private') {
        $path = $this->getBase($security).$dir.$file;
        $this->load->helper('download');
        $data = file_get_contents($path); // Read the file's contents
        force_download($file, $data);
    }
    
    public function registerpath($path, $security, $owner) {
        $datapath = $this->database_model->create('datapath');
        $datapath->path = $path;
        $datapath->security = $security;
        $datapath->owner = $owner;
        $this->database_model->save($datapath);
    }
    
    public function unregisterpath($path, $security) {
        $datapath = $this->database_model->findOne('datapath', ' path = ? and security = ? ', array($path, $security));
        if ($datapath) $this->database_model->delete('datapath', array($datapath->id));
    }
    
    public function createdir($base, $filename, $security, $owner, $mode = 0775) {
        $result = mkdir($base.$filename, 0755);
        if ($result) {
            $this->registerpath($filename, $security, $owner);
            return true;
        }
        return false;
    }
    
    public function uploadfile($base, $path, $overwrite, $security, $owner) {
        
        $config['upload_path'] = $base.$path;
        $config['allowed_types'] = 'gif|jpg|png|txt|pdf|map|zip';
        $config['max_size'] = '1000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['overwrite'] = (bool) $overwrite;

        $this->load->library('upload', $config);
        $this->load->helper(array('form'));

        if ( ! $this->upload->do_upload())
        {
            $data['error'] = $this->upload->display_errors();
        }
        else
        {
            $data['upload_data'] = $this->upload->data();
            $this->registerpath($path.$data['upload_data']['file_name'], $security, $owner);
        }
        return $data;
    }
    
    public function deldir($base, $filename, $security) {
        if (is_dir($base.$filename)) $result = rmdir($base.$filename);
        if ($result) $this->unregisterpath ($filename, $security);
        return $result;
    }
    
    public function delfile($base, $filename, $security) {
        if (is_file($base.$filename)) $result = unlink($base.$filename);
        if ($result) $this->unregisterpath ($filename, $security);
        return $result;
    }
    
}

?>
