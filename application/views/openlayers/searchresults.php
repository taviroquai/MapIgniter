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
<h2>Search Results</h2>
<?php if (empty($results)) : ?>
<p>No features found.</p>
<?php else : ?>
<ul>
    <?php foreach ($results as $pglayername => $pglayer) { ?>
    <li><span><?=$pglayer['pglayer']->layer->title?></span>
        <?php if (!empty($pglayer['records'])) : ?>
        <ul>
            <?php foreach ($pglayer['records'] as $item) { ?>
            <li>
                <h3>
                    <a href="javascript: <?=$_instance?>.loadFeature('<?=$pglayer['pglayer']->id?>', <?=$item['gid']?>, <?=$srid?>);">
                        <?php if (isset($config->config->display) && isset($config->config->display->$pglayername)) : ?>
                        <?=$item[$config->config->display->$pglayername]?>
                        <?php else: ?>
                        <?=empty($item['title']) ? empty($item['name']) ? '' : $item['name'] : $item['title']?>
                        <?php endif; ?>
                    </a>
                    <span><?php 
                        switch($item['geomtype']) {
                            case 'ST_MultiPolygon':
                            case 'ST_Polygon':
                                $item['img_src'] = base_url('/web/images/icons/polygon.png');
                                break;
                            case 'ST_MultiLinestring':
                            case 'ST_Linestring':
                                $item['img_src'] = base_url('/web/images/icons/linestring.png');
                                break;
                            case 'ST_Point':
                                $item['img_src'] = base_url('/web/images/icons/point.png');
                                break;
                            default: 
                                $item['img_src'] = base_url('/web/images/icons/geom.png');
                        }
                    ?><img style="vertical-align: middle" src="<?=$item['img_src']?>" alt="<?=$item['geomtype']?>" /></span>
                </h3>
                <p><?=empty($item['description']) ? '' : substr(strip_tags($item['description']), 0, 110).'...'?></p>
            </li>
            <?php } ?>
        </ul>
        <?php endif; ?>
    </li>
    <?php } ?>
</ul>
<?php endif; ?>


