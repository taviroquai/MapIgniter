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
<h2>Configure Menu</h2>
<? if (empty($menu)) : ?>
<p>The menu does not exists!</p>
<? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminmenus/save/<?=$menu->id?>">
        <label>Name</label>
        <input type="text" name="name" value="<?=$menu->name?>" />
        <button type="submit">Save</button>
    </form>
    <h3>New menu item</h3>
    <form method="post" action="<?=base_url()?>admin/adminmenus/saveitem/new">
        <label>Label</label>
        <input type="text" name="label" value="novo" /><br />
        <label>Path</label>
        <input type="text" name="href" value="/" /><br />
        <label>Internal path</label>
        <input type="checkbox" name="internal" value="1" checked="checked" /><br />
        <input type="hidden" name="menu_id" value="<?=$menu->id?>" />
        <button type="submit">Save</button>
    </form>
    <h3>Menu items</h3>
    <?
    if (empty($items)) : ?>
    <p>There are no items on this menu</p>
    <? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminmenus/deleteitem">
        <ul>
            <? foreach ($items as $item) { ?>
            <li>
                <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                <a href="<?=base_url()?>admin/adminmenus/edititem/<?=$item->id?>">Configure</a>
                <? if ($item->internal) $base = base_url();
                    else $base = '';?>
                <a href="<?=$base.$item->href?>"><?=$item->label?></a>
            </li>
            <? } ?>
        </ul>
        <input type="hidden" name="menu_id" value="<?=$menu->id?>" />
        <button type="submit">Remove selected</button>
    </form>
    <? endif;
endif;
?>