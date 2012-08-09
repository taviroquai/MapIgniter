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
<h2>Search Results</h2>
<? if (empty($results)) : ?>
<p>No features found.</p>
<? else : ?>
<ul>
    <? foreach ($results as $pglayername => $pglayer) { ?>
    <li><span><?=$pglayer['pglayer']->layer->title?></span>
        <? if (!empty($pglayer['records'])) : ?>
        <ul>
            <? foreach ($pglayer['records'] as $item) { ?>
            <li>
                <h3>
                    <a href="javascript: <?=$_instance?>.loadFeature('<?=$pglayer['pglayer']->id?>', <?=$item['gid']?>);">
                        <?=empty($item['title']) ? empty($item['name']) ? '' : $item['name'] : $item['title']?>
                    </a>
                    <span><? 
                        switch($item['geomtype']) {
                            case 'ST_MultiPolygon':
                            case 'ST_Polygon':
                                echo '<img src="'.base_url().'/web/images/icons/polygon.png" alt="'.$item['geomtype'].'" title="'.$item['geomtype'].'" />';
                                break;
                            case 'ST_MultiLinestring':
                            case 'ST_Linestring':
                                echo '<img src="'.base_url().'/web/images/icons/linestring.png" alt="'.$item['geomtype'].'" title="'.$item['geomtype'].'" />';
                                break;
                            case 'ST_Point':
                                echo '<img src="'.base_url().'/web/images/icons/point.png" alt="'.$item['geomtype'].'" title="'.$item['geomtype'].'" />';
                                break;
                            default: echo '<img src="'.base_url().'/web/images/icons/geom.png" alt="'.$item['geomtype'].'" title="'.$item['geomtype'].'" />';
                        }
                    ?></span>
                </h3>
                <p><?=empty($item['description']) ? '' : substr(strip_tags($item['description']), 0, 110).'...'?></p>
            </li>
            <? } ?>
        </ul>
        <? endif; ?>
    </li>
    <? } ?>
</ul>
<? endif; ?>


