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
?><h1>MapIgniter Installer</h1>

<form method="post" action="">
    <div class="four columns alpha">
    
        <h2>Configure Paths</h2>

        <label>Private data directory</label>
        <input type="text" name="private_data_path" value="<?=$post['private_data_path']?>" />

        <label>Public data directory</label>
        <input type="text" name="public_data_path" value="<?=$post['public_data_path']?>" />

        <label>MapServer image cache directory</label>
        <input type="text" name="cache_file_path" value="<?=$post['cache_file_path']?>" />

        <label>MapServer path</label>
        <input type="text" name="mapserver_path" value="<?=$post['mapserver_path']?>" />

        <label>MapServer CGI</label>
        <input type="text" name="mapserver_cgi" value="<?=$post['mapserver_cgi']?>" />

        <label>Postgresql path</label>
        <input type="text" name="psql_path" value="<?=$post['psql_path']?>" />

        <label>shp2psql path</label>
        <input type="text" name="shp2pgsql_path" value="<?=$post['shp2pgsql_path']?>" />

        <h2>Configure Email</h2>

        <label>Email origin address</label>
        <input type="text" name="ticket_email_origin" value="<?=$post['ticket_email_origin']?>" />

        <label>Email from name</label>
        <input type="text" name="ticket_email_name" value="<?=$post['ticket_email_name']?>" />

        <label>Email subject</label>
        <input type="text" name="ticket_email_subject" value="<?=$post['ticket_email_subject']?>" />

    </div>

    <div class="four columns">
        <h2>Configure Databases</h2>
        
        <fieldset>
            <legend>Application database</legend>
            
            
            <label>Driver</label>
            <input type="text" name="db_default_dbdriver" value="<?=$post['db_default_dbdriver']?>" disabled="disabled" />

            <label>Hostname</label>
            <input type="text" name="db_default_hostname" value="<?=$post['db_default_hostname']?>" />

            <label>Name</label>
            <input type="text" name="db_default_database" value="<?=$post['db_default_database']?>" />

            <label>Username</label>
            <input type="text" name="db_default_username" value="<?=$post['db_default_username']?>" />

            <label>Password</label>
            <input type="password" name="db_default_password" value="<?=$post['db_default_password']?>" />

        </fieldset>

        <fieldset>
            <legend>User data database</legend>
            
            <label>Driver</label>
            <input type="text" name="db_userdata_dbdriver" value="<?=$post['db_userdata_dbdriver']?>" disabled="disabled" />

            <label>Hostname</label>
            <input type="text" name="db_userdata_hostname" value="<?=$post['db_userdata_hostname']?>" />

            <label>Name</label>
            <input type="text" name="db_userdata_database" value="<?=$post['db_userdata_database']?>" />

            <label>Username</label>
            <input type="text" name="db_userdata_username" value="<?=$post['db_userdata_username']?>" />

            <label>Password</label>
            <input type="password" name="db_userdata_password" value="<?=$post['db_userdata_password']?>" />

        </fieldset>
        
      
    </div>
    <div class="eight columns omega">
        <h2>Checked Configuration</h2>
        
        <? if (empty($msgs['errors'])) : ?>
            <? if ($install) : ?>
                <p><strong>MapIgniter was installed successfully</strong></p>
                <p>Administrator login: admin<br />Password: admin</p>
		<p>IMPORTANT: delete this directory now</p>
                <p>Click <a href="<?=base_url('../')?>">here</a> to go to the homepage.</p>
            <? else: ?>
                <p><strong>No errors found</strong></p>
                <p>NOTE: You can now proceed to install.</p>
                <p>This operation will destroy current application database and cannot be undone.</p>
                <input type="hidden" name="install" value="1" />
                
                <label>&nbsp;</label>
                <button type="submit">Install MapIgniter</button>
            <? endif; ?>
        <? else: ?>
            <label>&nbsp;</label>
            <button type="submit">Check Configuration</button>
        <? endif; ?>
        <? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
        
    
    </div>
</form>
