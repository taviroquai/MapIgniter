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
<h2>Permissions</h2>
<ul class="tabs">
  <li><a class="active" href="#uriresources">URI Resources</a></li>
  <li><a href="#accountgroups">User Roles</a></li>
</ul>
<ul class="tabs-content">
    <li class="active" id="uriresources">
        <h3>New URI resource</h3>
        <form method="post" action="<?=base_url()?>admin/adminpermissions/save/new">
            <label>Regular expression (regex)</label>
            <input type="text" name="pattern" value="" />
            <button type="submit">Save</button>
        </form>
        <? if (empty($items)) : ?>
        <p>There are no URI resources yet.</p>
        <? else : ?>
        <h3>List of URI resources</h3>
        <form method="post" action="<?=base_url()?>admin/adminpermissions/delete">
            <ul>
                <? foreach ($items as $item) { ?>
                <li>
                    <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                    <a href="<?=base_url()?>admin/adminpermissions/edit/<?=$item->id?>">Configure</a>
                    <span><?=$item->pattern?></span>
                </li>
                <? } ?>
            </ul>
            <button type="submit">Remove selected</button>
        </form>
        <? endif; ?>
    </li>
    <li id="accountgroups">
        <h3>New User Role</h3>
        <form method="post" action="<?=base_url()?>admin/adminpermissions/savegroup/new">
            <label>Name</label>
            <input type="text" name="name" value="" />
            <button type="submit">Save</button>
        </form>
        <? if (empty($groups)) : ?>
        <p>There are no user roles yet.</p>
        <? else : ?>
        <h3>List of Roles</h3>
        <form method="post" action="<?=base_url()?>admin/adminpermissions/deletegroup">
            <ul>
                <? foreach ($groups as $item) { ?>
                <li>
                    <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                    <a href="<?=base_url()?>admin/adminpermissions/editgroup/<?=$item->id?>">Configure</a>
                    <span><?=$item->name?></span>
                </li>
                <? } ?>
            </ul>
            <button type="submit">Remove selected</button>
        </form>
        <? endif; ?>
    </li>
</ul>