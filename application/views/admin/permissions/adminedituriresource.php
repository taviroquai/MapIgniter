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
<h2>Configure URI Resource</h2>
<? if (empty($uriresource)) : ?>
<p>The URI resource does not exists!</p>
<? else : ?>
    <form method="post" action="<?=base_url()?>admin/adminpermissions/save/<?=$uriresource->id?>">
        <label>Regular expression (regex)</label>
        <input type="text" name="pattern" value="<?=$uriresource->pattern?>" />
        <button type="submit">Save</button>
    </form>
    <?
endif;
?>