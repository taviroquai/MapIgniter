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
<h2>Map on OpenLayers</h2>
<ul class="tabs">
  <li><a class="active" href="#editolmap">Configure</a></li>
  <? if (!empty($olmap->id)) : ?>
  <li><a href="#olmap-ollayers">Layers</a></li>
  <li><a href="#olmap-preview">Preview</a></li>
  <li><a href="#olmap-print">Print</a></li>
  <? endif; ?>
</ul>
<ul class="tabs-content">
<li class="active" id="editolmap">
<h3>Configure</h3>
<? if (empty($olmap)) : ?>
<p>The map does not exists!</p>
<? else : ?>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<? $this->load->view('admin/openlayers/adminmapform'); ?>
<? endif; ?>
</li>
<? if (!empty($olmap->id)) : ?>
<li id="olmap-ollayers">
<h3>Layers</h3>
<p><strong>Available layers</strong></p>
<?
$action = base_url().$ctrlpath.'/addlayer/'.$olmap->id;
$action_btn = 'Add selected';
$this->load->view('admin/openlayers/adminlayerlist', array('items' => $ollayers, 'action_btn' => $action_btn, 'action' => $action));

$items = $olmap->sharedOllayer;
if (empty($items)) : ?>
<p>There are no layers on this map</p>
<? else : ?>
<p><strong>Layers on this map</strong></p>
<?
    $action = base_url().$ctrlpath.'/dellayer/'.$olmap->id;
    $action_btn = 'Remove selected';
    $this->load->view('admin/openlayers/adminlayerlist', array('items' => $items, 'action_btn' => $action_btn, 'action' => $action)); ?>
<? endif; ?>
</li>
  <li class="active" id="olmap-preview">
      <h3>Preview</h3>
      <?
      if (empty($items)) : ?>
      <p>It is not possible to preview. There are no layers on the map.</p>
      <? else : 
          $links[] = base_url()."web/js/vendor/ol/theme/default/style.css";
          $links[] = base_url()."web/openlayers/mapblock.css";
          $scripts[] = base_url()."web/js/vendor/ol/OpenLayers.js";
          $scripts[] = base_url()."web/js/WebSig.js";
      ?>
      <div id="mapcontainer" style="width: 100%; height: 580px;">
          <div id="map_previewmap" class="divmap"></div>
      </div>
      <script type="text/javascript">
        var base_url = '<?=base_url()?>';
        $.noConflict();
        <?
        $i = 1;
        foreach ($links as $link) { ?>
        var link<?=$i?> = document.createElement('link');
        link<?=$i?>.rel = 'stylesheet';
        link<?=$i?>.href = '<?=$link?>';
        jQuery("body").append(link<?=$i?>);
        <? $i++; } ?>
        <? 
        $i = 1;
        foreach ($scripts as $script) { ?>
        var script<?=$i?> = document.createElement('script');
        script<?=$i?>.type = 'text/javascript';
        script<?=$i?>.src = '<?=$script?>';
        jQuery("body").append(script<?=$i?>);
        <? $i++; } ?>
        
        var block_previewmap;
        jQuery(document).ready(function($) {
            
            $.getJSON(base_url+'openlayers/getconfig/<?=$olmap->id?>', function(data) {
                block_previewmap = new WebSig.Mapblock('previewmap', data);
                block_previewmap.init();
                jQuery('#olmap-preview').show();
                block_previewmap.renderExtent();
                jQuery('#olmap-preview').hide();
            });
        });
        </script>
      <? endif;?>
  </li>
  <li id="olmap-print">
      <h3>Print</h3>
      <?
      if (empty($items)) : ?>
      <p>It is not possible to print. There are no layers on the map.</p>
      <? else : ?>
      <div id="printResponse"><a href="javascript: block_previewmap.print()">Print</a></div>
      <? endif; ?>
  </li>
<? endif; ?>
</ul>