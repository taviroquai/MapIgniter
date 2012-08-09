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
?><form method="post" action="<?=$action?>">
    <ul>
        <? foreach ($items as $item) {
        ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url().$mslayerctrlpath?>/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->layer->title?></span>
        </li>
        <? } ?>
    </ul>
    <button type="submit"><?=$action_btn?></button>
</form>