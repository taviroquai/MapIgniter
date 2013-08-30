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
?><h4>Classes list</h4>
<form method="post" action="<?=base_url().$msclassctrlpath?>/delete/<?=$mslayer->id?>">
    <ul>
        <? foreach ($items as $item) {
        ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url().$msclassctrlpath?>/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->name?></span>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>