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
<h2>Controller</h2>
<h3>Configure</h3>
<? if (empty($controller)) : ?>
<p>The controller does not exists!</p>
<? else : ?>
<form method="post" action="<?=base_url()?>admin/admincontrollers/save/<?=$controller->id?>">
    <label>Path</label>
    <input type="text" name="path" value="<?=$controller->path?>" />
    <label>Layout</label>
    <select name="layout_id">
        <? foreach ($layouts as $item) { ?>
        <option value="<?=$item->id?>" <?=$item->id == $controller->layout_id ? 'selected="selected"' : ''?>><?=$item->name?></option>
        <? } ?>
    </select>
    <button type="submit">Save</button>
</form>
<? endif; ?>
