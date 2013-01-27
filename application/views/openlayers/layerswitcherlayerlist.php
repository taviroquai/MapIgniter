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
?><?  foreach ($layers as $item) { ?>
<li id="layer_<?=$item->layer->alias?>">
    <input type="checkbox" value="<?=$item->id?>" data-layeralias="<?=$item->layer->alias?>" />
    <span class="milayer"><?=$item->layer->title?></span>
    <div style="float: left;"><? $this->load->view('rate', array('rate' => $rating[$item->layer->id])); ?></div>
    <div>
        <? if ($item->ollayertype_id == 4) : ?>
        <img src="<?=base_url().'mapserver/map/'.$item->url?>?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetLegendGraphic&LAYER=<?=$item->layer->alias?>&FORMAT=image/png" />
        <? endif; ?>
    </div>
</li>
<? } ?>