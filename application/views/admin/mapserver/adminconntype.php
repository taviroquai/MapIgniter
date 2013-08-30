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
<h2>MapServer - Connection type</h2>
<form method="post" action="<?=base_url()?>admin/adminmslayerconntype/save/new">
    <label>New</label>
    <input type="text" name="name" value="" />
    <button type="submit">Save</button>
</form>
<? if (empty($items)) : ?>
<p>There are no connection types</p>
<? else : ?>
<h3>List of connection types</h3>
<form method="post" action="<?=base_url()?>admin/adminmslayerconntype/delete">
    <ul>
        <? foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url()?>admin/adminmslayerconntype/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->name?></span>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<? endif; ?>