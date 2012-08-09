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
?>
<h2>User Accounts</h2>
<? $this->load->view('admin/accounts/adminaccountform', 
        array('ctrlpath' => $ctrlpath, 'account' => $account)); ?>
<? if (empty($items)) : ?>
<p>There are no user accounts yet.</p>
<? else : ?>
<h3>User accounts list</h3>
<form method="post" action="<?=base_url().$ctrlpath?>/delete">
    <ul>
        <? foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url().$ctrlpath?>/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->username?></span>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<? endif; ?>