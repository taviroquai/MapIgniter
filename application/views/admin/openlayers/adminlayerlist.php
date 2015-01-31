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
        <?php
        $order = 0;
        $last = count($items)-1;
        foreach ($items as $item) {
        ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url($ollayerctrlpath.'/edit/'.$item->id)?>">Configure</a>
            <span>
            <?php if (!empty($item->informationurl)) : ?>
                <a href="<?=$item->informationurl?>" target="_blank"><?=$item->layer->title?></a>
            <?php else : ?>
                <?=$item->layer->title?>
            <?php endif; ?>
            </span>
            <?php if (!empty($displayorder)) : ?>
            <span>
                <?php if ($order > 0) : ?>
                <a href="<?=base_url($ctrlpath.'/setLayerDisplayOrder/'.$olmap->id.'/'.$order.'/'.($order-1))?>">
                    <img src="<?=base_url('web/images/icons/png/16x16/arrow-up.png')?>" alt="enter" title="Up">
                </a><?php endif; ?>
                <?php if ($order < $last) : ?>
                <a href="<?=base_url($ctrlpath.'/setLayerDisplayOrder/'.$olmap->id.'/'.$order.'/'.($order+1))?>">
                    <img src="<?=base_url('web/images/icons/png/16x16/arrow-down.png')?>" alt="enter" title="Down">
                </a>
                <?php endif; ?>
            </span>
            <?php endif; ?>
        </li>
        <?php 
            $order++;
        } ?>
    </ul>
    <button type="submit"><?=$action_btn?></button>
</form>