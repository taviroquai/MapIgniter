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
<h2 class="map_title"><?=$item->map->title?></h2>
<div class="map_description"><?=$item->map->description?></div>
<?
if (empty($_instance) || empty($item)) : ?>
<p>There are missing parameters for this map:</p>
<ul>
    <li>Block instance name</li>
    <li>Map ID</li>
    <li>Map center (x, y)</li>
    <li>Zoom level</li>
</ul>
<? else : ?>
<div id="gemap_<?=$_instance?>" style="width: 100%; height: 100%;"></div>
<script type="text/javascript">
    var block_<?=$_instance?>;
    <?php if (empty($config)) { ?>
    google.load("earth", "1");
    <?php } else { ?>
    google.load("earth", "1", <?=json_encode($config)?>); 
    <?php } ?>

    function init_<?=$_instance?>() {
        google.earth.createInstance('gemap_<?=$_instance?>', initCB_<?=$_instance?>, failureCB_<?=$_instance?>);
    }

    function initCB_<?=$_instance?>(instance) {
        block_<?=$_instance?> = instance;
        block_<?=$_instance?>.getWindow().setVisibility(true);
        
        <? if (!empty($config['run'])) :
            foreach ($config['run'] as $item) { ?>
            if (window.block_<?=$item?>) {
                window.block_<?=$item?>.config(block_<?=$_instance?>);
            }
            <? } ?>
        <? endif; ?>
      
        var href = base_url + '/googleearth/kml/' + 1;

        google.earth.fetchKml(block_<?=$_instance?>, href, function(kmlObject) {
            if (kmlObject)
               block_<?=$_instance?>.getFeatures().appendChild(kmlObject);
            if (kmlObject.getAbstractView() !== null)
               block_<?=$_instance?>.getView().setAbstractView(kmlObject.getAbstractView());
      });
    }

    function failureCB_<?=$_instance?>(errorCode) {
        alert('Error loading Google Earth map');
    }

    google.setOnLoadCallback(init_<?=$_instance?>);
    
    function fixGoogleEarthViewport() {
        // Fix Google Earth Plugin viewport
        if (jQuery('#slot1 #gemap_<?=$_instance?>').length) {
            jQuery('#slot1').css('margin-top', '90px');
            jQuery('#slot1').css('margin-left', '388px');
            jQuery('#slot1').css('margin-right', '0');
            jQuery('#slot1').css('margin-bottom', '16px');
            jQuery('#slot1').css('width', jQuery(window).width() - jQuery('#column').width());
            jQuery('#slot1').css('height', jQuery(window).height() - jQuery('#slot-header').height() - 20 - 19);
        }
    }
    
    fixGoogleEarthViewport();
    jQuery(window).resize(fixGoogleEarthViewport);
</script>
<? endif; ?>