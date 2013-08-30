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
<h2>Controllers</h2>
<form method="post" action="<?=base_url()?>admin/admincontrollers/save/new">
    <fieldset>
        <legend>Register New</legend>
        <label>Path</label>
        <input type="text" name="path" value="" /><br />
        <label>Layout</label>
        <input type="text" name="view" value="" /><br />
        <button type="submit">Save</button>
    </fieldset>
</form>
<? if (empty($items)) : ?>
<p>There are no registered controllers</p>
<? else : ?>
<h3>List of controllers</h3>
<form method="post" action="<?=base_url()?>admin/admincontrollers/delete">
    <ul>
        <? foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url()?>admin/admincontrollers/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->path?></span>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<? endif; ?>