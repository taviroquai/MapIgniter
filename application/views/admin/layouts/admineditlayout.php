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
<h2>Configure layout</h2>
<? if (empty($layout)) : ?>
<p>The layout does not exists!</p>
<? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminlayouts/save/<?=$layout->id?>">
        <label>System name</label>
        <input type="text" name="name" value="<?=$layout->name?>" />
        <label>PHP View</label>
        <input type="text" name="view" value="<?=$layout->view?>" />
        <button type="submit">Save</button>
    </form>
    <h3>Create layout slot</h3>
    <form method="post" action="<?=base_url()?>admin/adminlayouts/saveslot/new">
        <label>System name</label>
        <input type="text" name="name" value="novo" />
        <input type="hidden" name="layout_id" value="<?=$layout->id?>" />
        <button type="submit">Save</button>
    </form>
    <h3>Layout slots list</h3>
    <?
    $items = $layout->ownLslot;
    if (empty($items)) : ?>
    <p>There are no slots registered on this layout</p>
    <? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminlayouts/deleteslot">
        <ul>
            <? foreach ($items as $item) { ?>
            <li>
                <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                <a href="<?=base_url()?>admin/adminlayouts/editslot/<?=$item->id?>">Configurar</a>
                <span><?=$item->name?></span>
            </li>
            <? } ?>
        </ul>
        <input type="hidden" name="layout_id" value="<?=$layout->id?>" />
        <button type="submit">Remove selected</button>
    </form>
    <? endif;
endif;
?>