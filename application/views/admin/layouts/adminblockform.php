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
?><form method="post" action="<?=base_url()?>admin/adminlayouts/saveblock/<?=$layout->id?>/<?=empty($block->id) ? 'new' : $block->id?>/<?=$slot->id?>">
    <label>Name</label>
    <input type="text" name="name" value="<?=$block->name?>" />
    <label>Module</label>
    <? if ($block->module) : ?>
    <p><?=$block->module->name?></p>
    <input type="hidden" name="module_id" value="<?=$block->module->id?>" />
    <? if ($module_items) : ?>
    <label>Instance</label>
    <select id="module_item" name="module_item">
        <? foreach ($module_items as $item) { 
            $itemname = empty($item->name) ? $item->title : $item->name;
            ?>
        <option value="<?=$item->id?>" <?=$item->id == $block->item ? 'selected="selected"' : ''?>><?=$itemname?></option>
        <? } ?>
    </select>
    <? endif; ?>
    <? else: ?>
    <select id="module_id" name="module_id">
        <? foreach ($modules as $item) { ?>
        <option value="<?=$item->id?>"><?=$item->name?></option>
        <? } ?>
    </select>
    <input type="hidden" name="module_item" value="" />
    <? endif; ?>
    <label>Adicional configuration (json)</label>
    <textarea name="config" rows="6" cols="80"><?=$block->config?></textarea>
    <button type="submit">Save</button>
</form>