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
?><div id="auth_menu" class="lblock">
<h2><?=$this->lang->line('title')?></h2>
<?php if (!empty($account)) : ?>
<p>
    <span><?=sprintf($this->lang->line('auth.account.info'), $account['username'])?></span>
    <a href="<?=base_url()?>user/user"><?=$this->lang->line('auth.menu.userlink')?></a>
    <a href="<?=base_url()?>auth/logout"><?=$this->lang->line('auth.menu.logout')?></a>
</p>

<?php else : ?>
<p>
    <a href="<?=base_url()?>auth"><?=$this->lang->line('auth.menu.login')?></a>
</p>
<?php endif; ?>
</div>