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
?><?php if (!empty($account)) : ?>
<h2>Session</h2>
<p>Hello <?=$account['username']?></p>
<form action="<?=base_url()?>auth" id="auth_form" method="post">
    <input type="hidden" name="logout" value="1" />
    <button type="submit">Logout</button>
</form>

<?php else : ?>
<h2>Start Session</h2>
<?php if (!empty($form['msg'])) : ?>
<div class="msgs"><p class="error"><?=$form['msg']?></p></div>
<?php endif; ?>
<form action="<?=base_url()?>auth" id="auth_form" method="post">
    <label for="auth_username">Username</label>
    <input type="text" id="auth_username" name="username" value="<?=$form['username']?>" />
    <label for="auth_password">Password</label>
    <input type="password" id="auth_password" name="password" value="<?=$form['password']?>" />
    <button type="submit">Login</button>
</form>
<p>
    <a href="<?=$gauth_url?>">
        <span>Login with </span>
        <img style="vertical-align: middle" src="<?=base_url()?>web/images/icons/google_logo.png" alt="Google Logo" title="Login with Google Account" />
    </a>
</p>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('form .msgs').animate({opacity:0},200,"linear",function(){
            $(this).animate({opacity:1},200);
        });
    });
</script>
<?php endif; ?>