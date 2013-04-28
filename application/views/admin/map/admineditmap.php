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
<h2>Configure Map</h2>
<? if (empty($map)) : ?>
<p>The map does not exists!</p>
<? else : ?>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<? $this->load->view('admin/map/adminmapform'); ?>
<? endif; ?>

<? if (!empty($pglayer)) : ?>
<h3>Postgis Layer</h3>
<a href="<?=base_url()?>user/managefullscreenpgplace/listitems/<?=$pglayer->id?>">Start Editing Places now!</a>
<? endif; ?>

<h3>MapServer</h3>
<?
$msmaps = $map->ownMsmapfile;
if (empty($msmaps)) : ?>
<p>This map does not exists on MapServer.
    <a href="<?=base_url().$msmapctrlpath?>/edit/new/<?=$map->id?>">Create map on MapServer</a>.
</p>
<? else : 
$item = reset($msmaps);
?>
<a href="<?=base_url().$msmapctrlpath?>/edit/<?=$item->id?>">Configure</a>
<a href="<?=base_url().$ctrlpath?>/delmsmap/<?=$item->id?>">Remove</a>
<? endif; ?>

<h3>OpenLayers</h3>
<?
$olmaps = $map->ownOlmap;
if (empty($olmaps)) : ?>
<p>This map does not exists on OpenLayers.
    <a href="<?=base_url().$olmapctrlpath?>/edit/new/<?=$map->id?>">Create map on OpenLayers</a>.
</p>
<? else : 
$item = reset($olmaps);
?>
<a href="<?=base_url().$olmapctrlpath?>/edit/<?=$item->id?>">Configure</a>
<a href="<?=base_url().$ctrlpath?>/delolmap/<?=$item->id?>">Remove</a>
<? endif; ?>

<h3>Google Earth</h3>
<?
$gemaps = $map->ownGemap;
if (empty($gemaps)) : ?>
<p>This map does not exists on Google Earth.
    <a href="<?=base_url().$gemapctrlpath?>/edit/new/<?=$map->id?>">Create map on Google Earth</a>.
</p>
<? else : 
$item = reset($gemaps);
?>
<a href="<?=base_url().$gemapctrlpath?>/edit/<?=$item->id?>">Configure</a>
<a href="<?=base_url().$ctrlpath?>/delgemap/<?=$item->id?>">Remove</a>
<? endif; ?>