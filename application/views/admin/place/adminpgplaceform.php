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
?><form id="pgplaceform" method="post" action="<?=base_url().$ctrlpath.$action?>">
    
    <label>GID / Geometry type</label>
    <p>
    <? if (empty($record['gid'])) : ?>
    <input type="hidden" name="gid" value="" />
    <span>new</span>
    <? else : ?>
        <span><?=$record['gid']?></span>
        <?
        $item = $record;
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
        ?><span><?=$record['geomtype']?></span>
    <? endif; ?>
    </p>
    
    <? foreach ($table->attributes as $field => $type) { 
        if (in_array($field, $sysfields)) continue;
        ?>
        <label><?=$field?>
            <a class="linkexplorer fancybox.ajax" title="Explorador" href="<?=base_url().$dataexplorerctrlpath?>?return=pgplace_<?=$field?>"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="explorador" title="Explorador" /></a>        
        </label>
        <? if ($table->attributes[$field] == 'text') : ?>
        <textarea id="pgplace_<?=$field?>" class="wysiwyg" name="<?=$field?>" style="width: 98%" rows="6"><?=$record[$field]?></textarea>
        <? else : ?>
        <input id="pgplace_<?=$field?>" type="text" name="<?=$field?>" value="<?=$record[$field]?>" />
        <? endif; ?>
    <? } ?>
    <button type="submit">Save</button>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("a.linkexplorer").fancybox({
            'height': 600,
            'autoDimensions': false,
            'width': 800
        });
    });
</script>