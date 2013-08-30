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
?><form method="post" action="<?=$action?>">
    <ul>
        <? foreach ($items as $item) {
        ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url().$gelayerctrlpath?>/edit/<?=$item->id?>">Configure</a>
            <span>
            <? if (!empty($item->informationurl)) : ?>
                <a href="<?=$item->informationurl?>" target="_blank"><?=$item->layer->title?></a>
            <? else : ?>
                <?=$item->layer->title?>
            <? endif; ?>
            </span>
        </li>
        <? } ?>
    </ul>
    <button type="submit"><?=$action_btn?></button>
</form>