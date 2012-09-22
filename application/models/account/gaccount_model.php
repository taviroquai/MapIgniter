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
require_once 'account_model.php';

class Gaccount_model extends Account_model {
    
    // TODO: move to outside configuration
    protected $client_id = '533558890471.apps.googleusercontent.com';
    protected $client_secret = 'VFcJ4_y8jXU5sY21P1nhc69d';

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function login_url() {
        $auth_url = 'https://accounts.google.com/o/oauth2/auth?';
        $params = array();
        $params[] = 'scope=https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email';
        $params[] = 'redirect_uri='.base_url().'gauth/logged';
        $params[] = 'response_type=code';
        $params[] = 'client_id='.$this->client_id;
        return $auth_url.implode('&', $params);
    }
    
    public function logged(&$errors) {
        
        $code = $this->input->get('code');
        if (empty($code)) {
            $errors[] = 'Unable to get Google oauth2 code';
            return false;
        }
        $googleaccess = $this->getAccessToken($code);
        if (empty($googleaccess->access_token)) {
            $errors[] = 'Unable to get Google oauth2 access token';
            return false;
        }
        
        // Get Google Account user name
        $url = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=".$googleaccess->access_token;
        $cURL = curl_init($url);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($cURL);
        curl_close($cURL);
        $googleaccount = json_decode($response);
        
        if (empty($googleaccount->name)) {
            $errors[] = 'Unable to get requested Google Account name';
            return false;
        }
        
        if (empty($googleaccount->email)) {
            $errors[] = 'Unable to get requested Google Account email';
            return false;
        }
        
        $googleaccount->name = str_replace(' ', '', $googleaccount->name);
        $account = $this->load($googleaccount->name);
        if (empty($account)) {
            $email = $googleaccount->email;
            $username = $googleaccount->name;
            $account = $this->create($email, $username);
            $this->gaccount_model->save($account);
        }

        return $account;
    }
    
    public function getAccessToken($code) {
        
        $url = 'https://accounts.google.com/o/oauth2/token';
        $fields = array(
            'code' => urlencode($code),
            'client_id' => urlencode($this->client_id),
            'client_secret' => urlencode($this->client_secret),
            'redirect_uri' => urlencode(base_url().'gauth/logged'),
            'grant_type' => urlencode('authorization_code')
        );
        //url-ify the data for the POST
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        $cURL = curl_init($url);
        curl_setopt($cURL,CURLOPT_POST, count($fields));
        curl_setopt($cURL,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($cURL);
        curl_close($cURL);

        return json_decode($response);
    }
}

?>
