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
 * @link		http://mapigniter.com/
 * @since		Version 1.1
 * @filesource
 */

// ------------------------------------------------------------------------

class Account_model extends CI_Model {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
        $this->load->model('account/group_model');
    }
    
    /**
     * Creates a new user account
     * @param string $email
     * @param string $username
     * @param string $password
     * @return RedBean_OODBBean
     */
    public function create($email = 'new@domain.tld', $username = 'new', $password = 'new')
    {
        $bean = $this->database_model->create('account');
        $bean->email = $email;
        $bean->username = $username;
        $bean->password = $this->secure($password);   
        return $bean;
    }
    
    /**
     * Saves a user account
     * @param RedBean_OODBBean $bean
     * @return RedBean_OODBBean
     */
    public function save(&$bean)
    {   
        return $this->database_model->save($bean);
    }
    
    /**
     * Loads a user account by username
     * @param string $username
     * @return RedBean_OODBBean|false
     */
    public function load($username) {
        return $this->database_model->findOne('account', ' username = ? ', array($username));
    }
    
    /**
     * Loas a user account by ID
     * @param integer $id
     * @return RedBean_OODBBean|false
     */
    public function loadById($id) {
        return $this->database_model->findOne('account', ' id = ? ', array($id));
    }
    
    /**
     * Loads all user accounts
     * @todo pagination
     * @return Array
     */
    public function loadAll() {
        return $this->database_model->find('account', ' true order by username');
    }
    
    /**
     * Deletes user accounts by an array of IDs
     * @param array $ids 
     */
    public function delete($ids) {
        $this->database_model->delete('account', $ids);
    }
    
    /**
     * Encrypts a user account password
     * @param string $password
     * @return string
     */
    public function secure($password) {
        $this->load->library('encrypt');
        return $this->encrypt->sha1($password);
    }
    
    /**
     * Saves user account form data
     * @param array $array
     * @param integer $id
     * @param array $errors
     * @param array $info
     * @return RedBean_OODBBean
     */
    public function saveAccountForm($array, $id, &$errors, &$info) {
        
        // Create or load account
        if ($id === 'new') $account = $this->create();
        else $account = $this->loadById($id);
        if (!$account) throw new Exception(sprintf($this->lang->line('error.account.load'), $id, base_url().$this->ctrlpath));

        // Validate data
        extract($array);
        if ($id === 'new') {
            $exists = $this->load($username);
            if ($exists) $errors[] = $this->lang->line('error.username.invalid');
        }
        if (empty($email)) $errors[] = $this->lang->line('error.email.invalid');
        if (empty($username)) $errors[] = $this->lang->line('error.username.invalid');

        // Set new data and save
        if (empty($errors)) {
            $account->email = $email;
            $this->database_model->save($account);
            $account->username = $username;
            $this->database_model->save($account);
            $info[] = $this->lang->line('info.account.save');

            if (!empty($password)) {
                $account->password = $this->secure($this->input->post('password'));
                $this->database_model->save($account);
                $info[] = $this->lang->line('info.password.save');
            }

            if (!empty($creategroup)) {
                $group = $this->group_model->load($username);
                if (!$group) {
                    $group = $this->group_model->create($username);
                    $this->group_model->addAccount($group, $account);
                    $info[] = sprintf($this->lang->line('info.creategroup.save'), $username);
                }
                else {
                    $this->group_model->addAccount($group, $account);
                    $info[] = sprintf($this->lang->line('info.addtogroup.save'), $username);
                }
            }
        }
        return $account;
    }
    
    /**
     * Saves user account groups
     * @param array $array
     * @param integer $id
     * @param array $errors
     * @param array $info
     * @return RedBean_OODBBean 
     */
    public function saveGroupsForm($array, $id, &$errors, &$info) {
        
        $account = $this->loadById($id);
        if (!$account) throw new Exception(sprintf($this->lang->line('error.account.load'), $id, base_url().$this->ctrlpath));
        
        extract($array);
        $account->sharedGroup = array();
        $this->database_model->save($account);
        $info[] = $this->lang->line('info.groups.delete');

        if (!empty($groups)) {
            foreach ($groups as $id) {
                $group = $this->group_model->loadById($id);
                $this->group_model->addAccount($group, $account);
                $group_str[] = $group->name;
            }
            $info[] = sprintf($this->lang->line('info.groups.save'), implode(',', $group_str));
        }
        return $account;
    }
    
    public function deleteAccountForm($array, &$errors, &$info) {
        extract($array);
        if (!empty($selected)) {
            $this->account_model->delete($selected);
            $info[] = $this->lang->line('info.accounts.delete');
        }
    }
    
    public function createVisit($account, $ip, $datetime) {
        $visit = $this->database_model->create('visit');
        $visit->account = $account;
        $visit->ip = $ip;
        $visit->last_update = $datetime;
        return $visit;
    }
    
    public function findVisit($account, $ip) {
        return $this->database_model->findOne('visit', ' account_id = ? and ip = ? and current_date = last_update::date ', array($account->id, $ip));
    }
    
}

?>
