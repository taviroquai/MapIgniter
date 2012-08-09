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
?><form method="post" action="<?=base_url()?>admin/adminlayouts/editslot/<?=empty($slot->id) ? 'new' : $slot->id?>/<?=$layout->id?>">
    <label>System name (must match the name in template)</label>
    <input type="text" name="name" value="<?=$slot->name?>" />
    <? if (empty($slot->layout)): ?>
    <input type="hidden" name="layout_id" value="<?=$layout->id?>" />
    <? endif; ?>
    <button type="submit">Save</button>
</form>