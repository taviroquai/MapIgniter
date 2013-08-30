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
?><form method="post" action="<?=base_url().$ctrlpath?><?=$action?>">
    
    <label>Title</label>
    <input type="text" name="title" value="<?=$layer->title?>" />

    <label>Alias (system name)</label>
    <input type="text" name="alias" value="<?=$layer->alias?>" />

    <? if (!empty($parentLayers)) : ?>
    <label>Sublayer of (optional)</label>
    <select name="parent">
        <option value="">Choose...</option>
        <? foreach ($parentLayers as $parent) { 
            if ($parent->id == $layer->id) continue;
            ?>
        <option value="<?=$parent->id?>"
                <?=$layer->fetchAs('layer')->parent == $parent ? 'selected="selected"' : ''?>><?=$parent->title?></option>
        <? } ?>
    </select>
    <? endif; ?>
    
    <label>Description</label>
    <textarea name="description" class="wysiwyg"><?=$layer->description?></textarea>

    <button type="submit">Save</button>
</form>