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
<h2>Configure layout slot</h2>
<? if (empty($slot)) : ?>
<p>The layout slot does not exists!</p>
<? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminlayouts/saveslot/<?=$layout->id?>/<?=$slot->id?>">
        <label>System name</label>
        <input type="text" name="name" value="<?=$slot->name?>" />
        <button type="submit">Save</button>
    </form>
<? endif; ?>
<a href="<?=base_url()?>admin/adminlayouts/edit/<?=$layout->id?>#editslots">Back to layout</a>