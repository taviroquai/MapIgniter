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
?><?php if (!empty($account)) : ?>
<h2>Session</h2>
<p>Hello <?=$account['username']?></p>
<p><a href="<?=base_url()?>auth/logout">Logout</a>
    
<?php else : ?>
<h2>Start Session</h2>
<?php if (!empty($msgs['errors'])) { $this->load->view('messages'); } ?>
<p>
    <a href="<?=$login_url?>">
        <span>Login with </span>
        <img style="vertical-align: middle" src="<?=base_url()?>web/images/icons/google_logo.png" alt="Google Logo" title="Login with Google Account" />
    </a>
</p>
<?php endif; ?>