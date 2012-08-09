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
?><form method="post" action="<?=base_url().$ctrlpath.$action?>">
    
    <label>Title</label>
    <p><?=$pglayer->layer->title?></p>
    <input type="hidden" name="layer_id" value="<?=$pglayer->layer->id?>" />
    
    <label>Postgis table</label>
    <select name="pgplacetype">
        <option value="new_pgplacetype">New table...</option>
        <? if (!empty($tables)) : ?>
            <? foreach ($tables as $item) { ?>
            <option value="<?=$item->name?>"<?=$item->name == $pglayer->pgplacetype ? ' selected="selected"' : ''?>><?=$item->name?></option>
            <? } ?>
        <? endif; ?>
    </select>
    <label>New table name (optional)</label>
    <input type="text" name="new_pgplacetype" value="<?=$pglayer->layer->alias?>" />
    
    <label>SRID</label>
    <select name="srid">
    <? foreach ($srid_list as $item) { ?>
        <option value="<?=$item['srid']?>" <?=$item['srid'] == $pglayer->srid ? 'selected="selected"' : ''?>><?=$item['auth_name'].':'.$item['srid']?></option>
    <? } ?>    
    </select>
    
    <label>Geometry type</label>
    <select name="type">
    <? foreach ($geom_types as $item) { ?>
        <option value="<?=$item?>" <?=$item == $pglayer->type ? 'selected="selected"' : ''?>><?=$item?></option>
    <? } ?>
    </select>
    
    <button type="submit">Save</button>
</form>