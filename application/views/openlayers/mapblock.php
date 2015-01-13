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
<h2 class="map_title"><?=$item->map->title?></h2>
<div class="map_description"><?=$item->map->description?></div>
<?php
if (empty($_instance) || empty($item) || empty($config)) : ?>
<p>There are missing parameters for this map:</p>
<ul>
    <li>Block instance name</li>
    <li>Map ID</li>
    <li>Map center (x, y)</li>
    <li>Zoom level</li>
</ul>
<?php else : ?>
<div id="mapcontainer" style="width: 100%; height: 100%;">
    <div id="map_<?=$_instance?>" class="divmap"></div>
</div>
<script type="text/javascript">
    var block_<?=$_instance?>;
    var date = new Date();
    jQuery(document).ready(function($) {
        $.getJSON(base_url+'openlayers/getconfig/<?=$item->id?>?'+date.getTime(), function(data) {
            block_<?=$_instance?> = new WebSig.Mapblock('<?=$_instance?>', data);
            block_<?=$_instance?>.init();
            <?php if (empty($config['center'])) : ?>
            block_<?=$_instance?>.renderExtent();
            <?php else : ?>
            block_<?=$_instance?>.render(<?=$config['center'][0]?>, <?=$config['center'][1]?>, <?=$config['zoom']?>);
            <?php endif; ?>
        });
    });
    
</script>
<?php endif; ?>