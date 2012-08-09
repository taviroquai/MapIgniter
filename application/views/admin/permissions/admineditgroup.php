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
<h2>Configure user role</h2>
<? if (empty($group)) : ?>
<p>The user role does not exists!</p>
<? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminpermissions/savegroup/<?=$group->id?>">
        <label>Name</label>
        <input type="text" name="name" value="<?=$group->name?>" />
        <button type="submit">Save</button>
    </form>
    <h3>New permission</h3>
    <form method="post" action="<?=base_url()?>admin/adminpermissions/saveitem/new">
        <label>URI Resource</label>
        <select name="uriresource_id">
            <? foreach ($uriresources as $uriresource) { ?>
            <option value="<?=$uriresource->id?>"><?=$uriresource->pattern?></option>
            <? } ?>
        </select><br />
        <label>Action</label>
        <select name="action">
            <option value="deny">Deny</option>
            <option value="allow">Allow</option>
        </select><br />
        <label>Expires</label>
        <input type="text" name="expire" value="0" /><br />
        <input type="hidden" name="group_id" value="<?=$group->id?>" />
        <button type="submit">Save</button>
    </form>
    <h3>List of Registered Permissions</h3>
    <?
    $items = $group->sharedPermission;
    if (empty($items)) : ?>
    <p>Ther are no permissions registered on this user role</p>
    <? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminpermissions/deleteitem">
        <ul>
            <? foreach ($items as $item) { ?>
            <li>
                <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                <a href="<?=base_url()?>admin/adminpermissions/edititem/<?=$item->id?>">Configure</a>
                <? $expire_str = empty($item->expire) ? '' : ' (expira a '.date('Y-m-d', $item->expire).')'; ?>
                <span><?=empty($item->action) ? 'NEGAR' : 'PERMITIR'?>: <?=$item->uriresource->pattern?><?=$expire_str?></span>
            </li>
            <? } ?>
        </ul>
        <input type="hidden" name="group_id" value="<?=$group->id?>" />
        <button type="submit">Remove selected</button>
    </form>
    <? endif;
endif;
?>