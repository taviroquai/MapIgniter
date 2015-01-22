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

class Urlproxy_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Executes the request
     * @param string $url
     * @return array
     */
    public function request($url, $post = array(), $log = true) {

        // Log network
        if ($log) $logfile = fopen($this->config->item('private_data_path')."curl.log", 'w') or die("can't open log file");
        
        //Start the Curl session
        $session = curl_init($url);

        // Attach post parameters
        $this->insertPost($session, $post);

        // Don't return HTTP headers. Do return the contents of the call
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_VERBOSE, true);
        if ($log) curl_setopt($session, CURLOPT_STDERR, $logfile); // logs curl messages
        curl_setopt($session, CURLOPT_FILETIME, true);

        // Make the call
        $content = curl_exec($session);

        // Get response headers
        $headers = curl_getinfo($session);
        
        // log anything
        if ($log) fwrite($logfile, json_encode($headers));

        // finish curl
        curl_close($session);
        
        // finish log
        if ($log) fclose($logfile);  // close logfile
        
        return array('headers' => $headers, 'content' => $content);
    }
    
    /**
     * Adds POST parameters to CURL request
     * @param curl_handle $session
     * @return curl_handle 
     */
    private function insertPost(&$session, $post) {
        // If it's a POST, put the POST data in the body
        if (!empty($post)) {
            curl_setopt ($session, CURLOPT_POST, true);
            if (is_string($post)) {
                curl_setopt($session, CURLOPT_HTTPHEADER, array("Content-type: application/xml", "Expect:"));
                curl_setopt ($session, CURLOPT_POSTFIELDS, $post);
            }
            else {
                $postvars = '';
                while ($element = current($post)) {
                    $postvars .= key($post).'='.$element.'&';
                    next($post);
                }
                curl_setopt ($session, CURLOPT_POSTFIELDS, $postvars);
            }
        }
        return $session;
    }
}

?>
