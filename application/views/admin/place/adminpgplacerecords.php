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

$total = count($items);
?>
<h2>Places in <?=$pglayer->layer->title?></h2>
<ul class="tabs">
    <li><a class="active" href="#placelist">List of places</a></li>
    <? if ($olmap) : ?>
    <li><a href="#editplacemap">Edition Map</a></li>
    <? endif; ?>
</ul>
<ul class="tabs-content">
    <li class="active" id="placelist">
        <form action="<?=base_url().$ctrlpath?>/listitems/<?=$pglayer->id?>" method="post">
            <legend>Filter</legend>
            <label>Expression</label>
            <input type="text" name="filter" value="<?=$filter?>" />

            <label>Values (; as separator)</label>
            <input type="text" name="values" value="<?=$values?>" />

            <label>Máx. number of results</label>
            <select name="limit">
                <? foreach ($limitopts as $opt) { ?>
                <option value="<?=$opt?>" <?=$opt == $limit ? 'selected="selected"' : ''?>><?=$opt?></option>
                <? } ?>
            </select>
            <button type="submit">Filter</button>
        </form>
        <? if (empty($items)) : ?>
        <p>No results.</p>
        <? else : ?>
        <p><?=$total?> loca<?=$total>1?'is':'l'?></p>
        <form method="post" action="<?=base_url().$ctrlpath?>/delete/<?=$pglayer->id?>">
            <button type="submit">Remove selected</button>
            <table class="placelist">
                <tr>
                    <th style="width: 50px;"></th>
                    <? foreach ($items[0] as $k => $v) {
                        if ($k === 'the_geom' || $k === 'geomtype' || $k === 'wkt') continue;
                        ?>
                    <th class="placegid"><?=$k?></th>
                    <? } ?>
                    <th>Type</th>
                    <th>Rating</th>
                </tr>
                <? foreach ($items as $item) { ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selected[]" value="<?=$item['gid']?>" />
                        <a href="<?=base_url().$ctrlpath?>/edit/<?=$pglayer->id?>/<?=$item['gid']?>">
                            <img src="<?=base_url()?>/web/images/icons/png/24x24/pencil.png" alt="Modify" title="Modify" />
                        </a></td>
                    <? foreach ($item as $k => $v) {
                        if ($k === 'the_geom' || $k === 'geomtype' || $k === 'wkt') continue;
                        ?>
                        <td><div class="rh"><?=strip_tags($v)?></div></td>
                    <? } ?>
                    <td><? 
                        switch($item['geomtype']) {
                            case 'ST_MultiPolygon':
                            case 'ST_Polygon':
                                echo '<img src="'.base_url().'/web/images/icons/polygon.png" alt="'.$item['geomtype'].'" title="'.$item['geomtype'].'" />';
                                break;
                            case 'ST_MultiLineString':
                            case 'ST_LineString':
                                echo '<img src="'.base_url().'/web/images/icons/linestring.png" alt="'.$item['geomtype'].'" title="'.$item['geomtype'].'" />';
                                break;
                            case 'ST_Point':
                                echo '<img src="'.base_url().'/web/images/icons/point.png" alt="'.$item['geomtype'].'" title="'.$item['geomtype'].'" />';
                                break;
                            default: echo '<img src="'.base_url().'/web/images/icons/geom.png" alt="'.$item['geomtype'].'" title="'.$item['geomtype'].'" />';
                        }
                    ?></td>
                    <td style="width: 90px;"><? if (!empty($rating)) $this->load->view('rate', array('rate' => $rating[$pglayer->layer->alias.'.'.$item['gid']], 'nolabel' => true)); ?></td>
                </tr>
                <? } ?>
            </table>
        </form>
        <? endif; ?>
    </li>
    <? if ($olmap) : ?>
    <li id="editplacemap">
        <h3><?=$olmap->map->title?></h3>
        <?
        $items = $olmap->sharedOllayer;
        if (empty($items)) : ?>
            <p>It is not possible to view the map. There are no layers on this map.</p>
        <? else : ?>
            <p>
                <a href="<?=base_url().$fullscreenctrl?>/listitems/<?=$pglayer->id?>">
                    <img style="vertical-align: middle;" src="<?=base_url()?>web/images/icons/png/24x24/full-screen.png" alt="Enter fullscreen mode" title="Enter fullscreen mode" /><span>Enter fullscreen mode</span>
                </a>
            </p>
        <? endif; ?>
    </li>
    <? endif; ?>
</ul>