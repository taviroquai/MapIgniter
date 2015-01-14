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

$total = count($items);

// Extract SRID for geometry transformations
$srid = str_replace('EPSG:', '', $olmap->projection);
?>
<?php if (empty($items)) : ?>
<p>No places found.</p>
<?php else : ?>

<p>Count: <?=$total?></p>
<table class="placelist">
    <tr>
        <th></th>
        <?php foreach ($items[0] as $k => $v) {
            if ($k === 'the_geom' || $k === 'geomtype' || $k === 'wkt') continue;
        ?>
        <th class="placegid"><?=$k?></th>
        <?php } ?>
        <th>Type</th>
    </tr>
    <?php foreach ($items as $item) { ?>
    <tr>
        <td>
            <button class="edit" data-feature-id="<?=$item['gid']?>" data-srid="<?=$srid?>" style="margin-bottom: 0; padding: 1px">
                <img src="<?=base_url()?>/web/images/icons/png/24x24/pencil.png" alt="Modificar" title="Modificar" />
            </button>
        </td>
        <?php foreach ($item as $k => $v) {
            if ($k === 'the_geom' || $k === 'geomtype' || $k === 'wkt') continue;
            ?>
            <td><div class="rh"><?=strip_tags($v)?></div></td>
        <?php } ?>
        <td><?php 
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
    </tr>
    <?php } ?>
</table>
<?php endif; ?>