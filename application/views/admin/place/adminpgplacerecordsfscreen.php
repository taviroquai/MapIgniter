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
<h2>Edit - <?=$table->name?></h2>
<p><a href="<?=base_url().$pgplacectrl?>/listitems/<?=$pglayer->id?>">
    <img style="vertical-align: middle;" src="<?=base_url()?>web/images/icons/png/24x24/arrow-left.png" alt="Sair do modo ecrã inteiro" title="Exit fullscreen mode" />Exit fullscreen mode.
</a></p>

<ul class="tabs">
    <li><a class="active" href="#placelist">List of places</a></li>
    <li><a href="#listfilter">Filter</a></li>
    <li><a href="#editoptions">Options</a></li>
</ul>
<ul class="tabs-content">
    <li class="active" id="placelist">
        <div style="width: 100%; text-align: center;">
            <label id="editbutton">
                <input style="display: none;" type="checkbox" id="create" name="create" value="1" onclick="editfeaturecontrol.toggleCreate();"/>
                <span>Draw a new place</span>
            </label>
        </div>
        <? if (empty($items)) : ?>
        <p>No places found.</p>
        <? else : ?>
        
        <p><?=$total?> loca<?=$total>1?'is':'l'?></p>
        <table class="placelist">
            <tr>
                <th></th>
                <? foreach ($items[0] as $k => $v) {
                    if ($k === 'the_geom' || $k === 'geomtype' || $k === 'wkt') continue;
                    ?>
                <th class="placegid"><?=$k?></th>
                <? } ?>
                <th>Type</th>
            </tr>
            <? foreach ($items as $item) { ?>
            <tr>
                <td><a href="javascript: editfeaturecontrol.loadFeature('<?=$item['gid']?>');">
                        <img src="<?=base_url()?>/web/images/icons/png/24x24/pencil.png" alt="Modificar" title="Modificar" />
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
            </tr>
            <? } ?>
        </table>

        <? endif; ?>
        <?
        $items = $olmap->sharedOllayer;
        if (empty($items)) : ?>
            <p>It is not possible to view the map. There are no layers on this map.</p>
        <? else :
          $links[] = base_url()."web/js/vendor/ol/theme/default/style.css";
          $links[] = base_url()."web/openlayers/mapblock.css";
          $scripts[] = base_url()."web/js/vendor/ol/OpenLayers.js";
          $scripts[] = base_url()."web/js/WebSig.js";
          $scripts[] = base_url()."web/openlayers/wfseditfeature.js";
        ?>
        <div id="pgplaceformcontainer" style="display: none;"></div>
        <script type="text/javascript">
        var base_url = '<?=base_url()?>';
        var ctrlpath = '<?=$ctrlpath?>';
        $.noConflict();
        <?
        $i = 1;
        foreach ($links as $link) { ?>
        var link<?=$i?> = document.createElement('link');
        link<?=$i?>.rel = 'stylesheet';
        link<?=$i?>.href = '<?=$link?>';
        jQuery("head").append(link<?=$i?>);
        <? $i++; } ?>
        <? 
        $i = 1;
        foreach ($scripts as $script) { ?>
        var script<?=$i?> = document.createElement('script');
        script<?=$i?>.type = 'text/javascript';
        script<?=$i?>.src = '<?=$script?>';
        jQuery("head").append(script<?=$i?>);
        <? $i++; } ?>

        var block_editplacemap, editfeaturecontrol;
        jQuery(document).ready(function($) {
            fixLayout($);
            $(window).resize(function () {
                fixLayout(jQuery);
            });
            
            $.getJSON(base_url+'openlayers/getconfig/<?=$olmap->id?>', function(data) {
                block_editplacemap = new WebSig.Mapblock('editplacemap', data);
                block_editplacemap.init();
                block_editplacemap.renderExtent();
                block_editplacemap.map.zoomTo(1);
                editfeaturecontrol = new wfseditfeature(block_editplacemap, '<?=$pglayer->id?>', <?=$editlayerindex?>, '<?=$table->type?>');
            });
        });
        
        function fixLayout($) {
            // Fix map height
            $('#editwrapper').height($('body').height() - $('div.header').height());
            // Fix Map Width
            $('#map_editplacemap').width($('body').width() - $('#content').width() - 15);
        }
        </script>
    <? endif; ?>
    </li>
    <li id="listfilter">
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
    </li>
    <li id="editoptions">
        <input type="checkbox" id="autosave" name="autosave" checked="checked" value="1" />
        <span>Save geometry changes</span>
    </li>
</ul>