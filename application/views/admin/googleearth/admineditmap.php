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
<h2>Map on Google Earth</h2>
<ul class="tabs">
  <li><a class="active" href="#editgemap">Configure</a></li>
  <?php if (!empty($gemap->id)) : ?>
  <li><a href="#gemap-gelayers">Layers</a></li>
  <li><a href="#gemap-preview">Preview</a></li>
  <?php endif; ?>
</ul>
<ul class="tabs-content">
<li class="active" id="editgemap">
<h3>Configure</h3>
<?php if (empty($gemap)) : ?>
<p>The map does not exists!</p>
<?php else : ?>
<?php if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<?php $this->load->view('admin/googleearth/adminmapform'); ?>
<?php endif; ?>
</li>
<?php if (!empty($gemap->id)) : ?>
<li id="gemap-gelayers">
<h3>Layers</h3>
<p><strong>Available layers</strong></p>
<?php
$action = base_url().$ctrlpath.'/addlayer/'.$gemap->id;
$action_btn = 'Add selected';
$this->load->view('admin/googleearth/adminlayerlist', array('items' => $gelayers, 'action_btn' => $action_btn, 'action' => $action));

$items = $gemap->sharedGelayer;
if (empty($items)) : ?>
<p>There are no layers on this map</p>
<?php else : ?>
<p><strong>Layers on this map</strong></p>
<?php
    $action = base_url().$ctrlpath.'/dellayer/'.$gemap->id;
    $action_btn = 'Remove selected';
    $this->load->view('admin/googleearth/adminlayerlist', array('items' => $items, 'action_btn' => $action_btn, 'action' => $action)); ?>
<?php endif; ?>
</li>
  <li class="active" id="gemap-preview">
      <h3>Preview</h3>
      <div id="mapcontainer">
          <div id="map_previewmap" style="width: 100%; height: 580px;"></div>
      </div>
      <script type="text/javascript">
        var base_url = '<?=base_url()?>';
        $.noConflict();
        
        var block_previewmap;

        function init() {
            google.load("earth", "1", {"callback" : earth_loaded});
        }
        
        function earth_loaded() {
            jQuery.getJSON(base_url+'googleearth/getconfig/<?=$gemap->id?>', function(data) {
                google.earth.createInstance('map_previewmap', initCB, failureCB);
            });
        }

        function initCB(instance) {
            block_preview = instance;
            block_preview.getWindow().setVisibility(true);
        }

        function failureCB(errorCode) {
            alert('Error loading Google Earth map');
        }

        jQuery(document).ready(function($) {
            var script = document.createElement("script");
            script.src = "https://www.google.com/jsapi?callback=init";
            script.type = "text/javascript";
            document.getElementsByTagName("head")[0].appendChild(script);
        });
      </script>
  </li>
<?php endif; ?>
</ul>