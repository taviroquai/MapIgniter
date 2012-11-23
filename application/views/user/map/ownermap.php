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
?>
<h2>My Maps</h2>
<a class="novo" href="#">Create a new map</a>
<div class="accordion">
    <? $this->load->view('user/map/ownermapform'); ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('div.accordion').hide();
        $('a.novo').click(function() {
            $(this).parent().find('div.accordion').slideToggle("slow");
	});
    });
</script>
<? if (empty($items)) : ?>
<p>There are no maps</p>
<? else : ?>
<form method="post" action="<?=base_url().$ctrlpath?>/delete">
    <ul>
        <? foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url().$ctrlpath?>/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->title?></span>
            <? $this->load->view('rate', array('rate' => $rating[$item->id])); ?>
            <?
            $msmapfiles = $item->ownMsmapfile;
            if (!empty($msmapfiles)) {
                $msmapfile = reset($msmapfiles);
                $items = $msmapfile->sharedMslayer;
                if (empty($items)) : ?>
                <p>It is not possible to view the map. There are no layers on this map.</p>
                <? else : reset ($items); ?>
                <ul id="maplist">
                <? foreach ($items as $item) { 
                    $img_link = base_url().'mapserver/map/'.$msmapfile->map->alias.'?mode=map&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&LAYERS='.$item->layer->alias.'&no-cache='.rand(1,9999);
                    ?>
                    <li><a href="<?=$img_link?>">
                            <img src="<?=$img_link?>" style="width:200px; border: 6px solid #e0e0e0;" alt="<?=$item->layer->title?>" title="<?=$item->layer->title?>"/>
                        </a>
                    </li>
                
                <? } ?>
                </ul>
                <br style="clear: both;" />
              <? endif;
            }
            ?>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<? endif; ?>