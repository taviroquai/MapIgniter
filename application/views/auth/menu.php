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
?>
<?php if (!empty($account)) : ?>
    <li><a href="#"><?=sprintf($this->lang->line('auth.account.info'), $account['username'])?></a></li>
    <li><a href="<?=base_url()?>user/user"><?=$this->lang->line('auth.menu.userlink')?></a></li>
    <li><a href="<?=base_url()?>auth/logout"><?=$this->lang->line('auth.menu.logout')?></a></li>
<?php else : ?>
    <li><a href="<?=base_url()?>auth"><?=$this->lang->line('auth.menu.login')?></a></li>
<?php endif; ?>
