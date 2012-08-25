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
?><form method="post" action="<?=base_url().$ctrlpath?><?=$action?>">
    
    <label>Title</label>
    <input type="text" name="title" value="<?=$map->title?>" />

    <label>System name (alias)</label>
    <input type="text" name="alias" value="<?=$map->alias?>" />

    <label>Description</label>
    <textarea name="description" class="wysiwyg"><?=$map->description?></textarea>
    
    <? if (empty($map->id)) : ?>
    <label>
        <input type="checkbox" name="auto" value="1" checked="checked" />
        <span>Auto (creates all Postgis, MapServer and OpenLayers default items)</span>
    </label>
    
    <label>Postgis table</label>
    <select name="pgplacetype">
        <option value="new_pgplacetype">New table...</option>
        <? if (!empty($tables)) : ?>
            <? foreach ($tables as $item) { ?>
            <option value="<?=$item->name?>"><?=$item->name?></option>
            <? } ?>
        <? endif; ?>
    </select>
    
    <label>SRID</label>
    <select name="srid">
    <? foreach ($srid_list as $item) { ?>
        <option value="<?=$item['srid']?>" <?=$item['srid'] == '3857' ? 'selected="selected"': ''?>><?=$item['auth_name'].':'.$item['srid']?></option>
    <? } ?>    
    </select>
    
    <label>Geometry type</label>
    <select name="type">
    <? foreach ($geom_types as $item) { ?>
        <option value="<?=$item?>"><?=$item?></option>
    <? } ?>
    </select>
    <? endif; ?>

    <button type="submit">Save</button>
</form>