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
<h2>Configure layout slot</h2>
<? if (empty($slot)) : ?>
<p>The layout slot does not exists!</p>
<? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminlayouts/saveslot/<?=$slot->id?>">
        <label>System name</label>
        <input type="text" name="name" value="<?=$slot->name?>" />
        <button type="submit">Save</button>
    </form>
    <h3>Add block to slot</h3>
    <form method="post" action="<?=base_url()?>admin/adminlayouts/saveblock/new">
        <label>Instance name</label>
        <input type="text" name="name" value="novo" />
        
        <label>Module</label>
        <select name="module_id">
            <? foreach ($modules as $module) { ?>
            <option value="<?=$module->id?>"><?=$module->name?></option>
            <? } ?>
        </select>
        <input type="hidden" name="slot_id" value="<?=$slot->id?>" />
        <button type="submit">Save</button>
    </form>
    <h3>List of configured blocks on this slot </h3>
    <?
    $items = $slot->sharedLblock;
    if (empty($items)) : ?>
    <p>There are no blocks on this slot</p>
    <? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminlayouts/deleteblock">
        <ul>
            <? foreach ($items as $item) {
            ?>
            <li>
                <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                <a href="<?=base_url()?>admin/adminlayouts/editblock/<?=$item->id?>">Configure</a>
                <span><?=$item->name?> (<?=$item->module->name?>)</span>
            </li>
            <? } ?>
        </ul>
        <input type="hidden" name="slot_id" value="<?=$slot->id?>" />
        <button type="submit">Remove selected</button>
    </form>
    <? endif;
endif;
?>