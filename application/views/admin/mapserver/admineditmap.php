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
<h2>Map on Mapserver</h2>
<ul class="tabs">
  <li><a class="active" href="#editmsmap">Configure</a></li>
  <? if (!empty($msmapfile->id)) : ?>
  <li><a href="#msmap-metadata">Metadata</a></li>
  <li><a href="#msmap-mslayers">Layers</a></li>
  <li><a href="#msmap-mslegend">Legend</a></li>
  <li><a href="#msmap-preview">Preview</a></li>
  <li><a href="#msmap-info">Informations</a></li>
  <? endif; ?>
</ul>
<ul class="tabs-content">
  <li class="active" id="editmsmap">
<h3>Configure</h3>
<? if (empty($msmapfile)) : ?>
<p>The map does not exists!</p>
<? else : ?>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<? $this->load->view('admin/mapserver/adminmapform'); ?>
<? endif; ?>
  </li>
  <li id="msmap-metadata">
<? if (!empty($msmapfile->id)) : ?>
<h3>Metadata</h3>
<form method="post" action="<?=base_url().$ctrlpath?>/savemetadata/new/<?=$msmapfile->id?>">
    <fieldset>
        <legend>Add Item</legend>
        <div class="accordion">
            <label>Metadata item</label>
            <select name="msmetadata_id">
            <? foreach ($msmetadata as $item) { ?>
                <option value="<?=$item->id?>"><?=$item->name?></option>
            <? } ?>    
            </select>
            <label>Value</label>
            <input type="text" name="value" value="" />
            <button type="submit">Add</button>
        </div>
    </fieldset>
</form>
<?
$items = $msmapfile->ownMsmapfilemd;
if (empty($items)) : ?>
<p>There are no metadata items on this map</p>
<? else : ?>
<form method="post" action="<?=base_url().$ctrlpath?>/delmetadata/<?=$msmapfile->id?>">
    <ul>
        <? foreach ($items as $item) {
        ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <span><?=$item->msmetadata->name?> <?=$item->value?></span>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<? endif; ?>
  </li>
  <li id="msmap-mslayers">
<h3>Layers</h3>
<p><strong>Available layers</strong></p>
<?
$action = base_url().$ctrlpath.'/addlayer/'.$msmapfile->id;
$action_btn = 'Adicionar Selecionados';
$this->load->view('admin/mapserver/adminlayerlist', array('items' => $mslayers, 'action_btn' => $action_btn, 'action' => $action));

$items = $msmapfile->sharedMslayer;
if (empty($items)) : ?>
<p>There are no layers on this map</p>
<? else : ?>
<p><strong>Layers on this map</strong></p>
<?
    $action = base_url().$ctrlpath.'/dellayer/'.$msmapfile->id;
    $action_btn = 'Remove selected';
    $this->load->view('admin/mapserver/adminlayerlist', array('items' => $items, 'action_btn' => $action_btn, 'action' => $action)); ?>
<? endif; ?>
  </li>
  <li id="msmap-mslegend">

<h4>Legend</h4>
<?
$legends = $msmapfile->ownMslegend;
if (empty($legends)) : ?>
<p>There is no legend for this map
Click <a href="<?=base_url().$mslegendctrlpath?>/edit/new/<?=$msmapfile->id?>">here</a> to create a legend.
</p>
<? else : 
    $legend = reset($legends);
?>
<p>
    <a href="<?=base_url().$mslegendctrlpath?>/edit/<?=$legend->id?>">Configure</a>
    <a href="<?=base_url().$ctrlpath?>/dellegend/<?=$msmapfile->id?>">Remove</a>
</p>
<? if (empty($items)) : ?>
      <p>Is not possible to view the legend. There are no layers on this map.</p>
      <? else :
      reset ($items);
      foreach ($items as $item) { 
          $img_link = base_url().'mapserver/map/'.$msmapfile->map->alias.'?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetLegendGraphic&LAYER='.$item->layer->alias.'&FORMAT=image/png';
      ?>
      <p>
          Layer legend <?=$item->layer->title?><br />
          <img src="<?=$img_link?>" />
        </p>
      <? }
      endif;?>

<? endif; ?>
</li>
  <li id="msmap-preview">
      <h3>Preview</h3>
      <p>
          <a href="<?=base_url().$ctrlpath?>/updatemapfile/<?=$msmapfile->id?>#msmap-preview">Click to update the mapfile</a>
      </p>
      <?
      if (empty($items)) : ?>
      <p>It is not possible to preview the map. There are no layers on this map.</p>
      <? else :
      reset ($items);
      foreach ($items as $item) { 
          $img_link = base_url().'mapserver/map/'.$msmapfile->map->alias.'?mode=map&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&LAYERS='.$item->layer->alias;
      ?>
      <p>
          Layer <?=$item->layer->title?><br />
          <a href="<?=$img_link?>">
            <img src="<?=$img_link?>" />
          </a>
      </p>
      <? }
      endif;?>
  </li>
  <li id="msmap-info">
      <h3>Informations</h3>
      <label>Map</label>
      <ul>
          <li>URL: <a href="<?=base_url()?>mapserver/map/<?=$msmapfile->map->alias?>?"><?=base_url()?>mapserver/map/<?=$msmapfile->map->alias?>?</a></li>
          <li><a href="<?=base_url()?>mapserver/map/<?=$msmapfile->map->alias?>?SERVICE=WMS&REQUEST=GetCapabilities">WMS GetCapabilities</a></li>
          <li><a href="<?=base_url()?>mapserver/map/<?=$msmapfile->map->alias?>?SERVICE=WFS&VERSION=1.0.0&REQUEST=GetCapabilities">WFS GetCapabilities</a></li>
      </ul>
      <label>WFS layers</label>
      <ul>
      <? foreach ($items as $item) { ?>
          <li><a href="<?=base_url()?>mapserver/map/<?=$msmapfile->map->alias?>?SERVICE=WFS&VERSION=1.0.0&REQUEST=GetFeature&TYPENAME=<?=$item->layer->alias?>">WFS <?=$item->layer->title?></a></li>
      <? } ?>
      </ul>
  </li>
<? endif; ?>
</ul>