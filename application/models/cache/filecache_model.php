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

class Filecache_model extends CI_Model {
    
    protected $units = array('B','KB','MB','GB','TB','PB');
    protected $path;
    protected $expire = 300;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->path = $this->config->item('cache_file_path');
        $this->expire = $this->config->item('cache_expire');
    }
    
    public function getExpireTime() {
        return $this->expire;
    }
    
    public function getKey($name) {
        if (is_array($name)) $name = implode('_', $name);
        return 'mi_'.sha1($name);
    }
    
    public function getItemPath($key, $format) {
        return $this->path.$key.'.'.$format;
    }
    
    public function isCached($name, $format) {
        $filepath = $this->getItemPath($this->getKey($name), $format);
        return file_exists($filepath);
    }
    
    public function outputItem($name, $format) {

        $cached = $this->isCached($name, $format);
        $expired = $this->expire;

        // Checking if the client is validating his cache and if it is current.
        if ($cached) {
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= time())) {
                // Client's cache IS current, so we just respond '304 Not Modified'.
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()+$expired).' GMT', true, 304);
                header("MICache: skipped");
            }
            else {
                // Client's cache expired so we return the cached file
                header('Cache-Control: max-age='.$expired);
                $tsstring = gmdate('D, d M Y H:i:s', strtotime('+5 minutes')).' GMT';
                header("Last-Modified: $tsstring");
                header("Expires: ".$tsstring);
                header("MICache: cached");

                header('Content-Type: image/'.$format);
                echo $this->loadItem($name, $format);
            }
            exit();
        }
    }
    
    public function loadItem($name, $format) {
        $filepath = $this->getItemPath($this->getKey($name), $format);
        return file_get_contents($filepath);
    }
    
    public function saveItem($name, $content, $format) {
        if (!is_dir($this->path)) mkdir ($this->path);
        $filepath = $this->getItemPath($this->getKey($name), $format);
        file_put_contents($filepath, $content);
    }
    
    public function prob_clear() {
        // Only run on very low probability
        if (rand(1, 100) <= 1) {
            $this->clear();
        }
    }
    
    public function clear() {
        $this->load->helper('file');
            
        $files = get_dir_file_info($this->path);
        foreach ($files as $filename => $info) {
            if ($info['date'] < time() - $this->expire) {
                unlink($this->path.$filename);
            }
        }
    }

    public function getTotalItems() {
        $this->load->helper('file');
        $files = get_dir_file_info($this->path);
        return count($files);
    }

    public function getSize() {
        $total_size = 0;
        $files = scandir($this->path);
        $cleanPath = rtrim($this->path, '/'). '/';

        foreach($files as $t) {
            if ($t<>"." && $t<>"..") {
                $currentFile = $cleanPath . $t;
                if (is_dir($currentFile)) {
                    $size = foldersize($currentFile);
                    $total_size += $size;
                }
                else {
                    $size = filesize($currentFile);
                    $total_size += $size;
                }
            }   
        }

        return $total_size;
    }


    public function formatSize($size) {

        $mod = 1024;

        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        $endIndex = strpos($size, ".")+3;

        return substr( $size, 0, $endIndex).' '.$this->units[$i];
    }
}

?>
