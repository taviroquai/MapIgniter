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
    <input type="text" name="title" value="<?=$map->title?>" />

    <label>System name (alias)</label>
    <input type="text" name="alias" value="<?=$map->alias?>" />
    
    <label>Owner</label>
    <? $owner = $map->fetchAs('account')->owner; ?>
    <input type="text" name="owner" value="<?=$owner ? $owner->username : ''?>" />

    <label>Description</label>
    <textarea name="description" class="wysiwyg"><?=$map->description?></textarea>

    <button type="submit">Save</button>
</form>