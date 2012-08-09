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
<h2>Configure Layer</h2>
<? if (empty($layer)) : ?>
<p>The layer does not exists!</p>
<? else : ?>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<? $this->load->view('admin/layer/adminlayerform'); ?>
<?    
endif;
?>

<h3>Postgis</h3>
<?
$pglayers = $layer->ownPglayer;
if (empty($pglayers)) : ?>
<p><a href="<?=base_url().$pglayerctrlpath?>/edit/new/<?=$layer->id?>">Create layer on Postgis</a></p>
<? else : 
$item = reset($pglayers);
?>
<a href="<?=base_url().$pglayerctrlpath?>/edit/<?=$item->id?>">Configure</a>
<a href="<?=base_url().$ctrlpath?>/delpglayer/<?=$item->id?>">Remove</a>
<? endif; ?>

<h3>MapServer</h3>
<?
$mslayers = $layer->ownMslayer;
if (empty($mslayers)) : ?>
<p><a href="<?=base_url().$mslayerctrlpath?>/edit/new/<?=$layer->id?>">Create layer on MapServer</a></p>
<? else : 
$item = reset($mslayers);
?>
<a href="<?=base_url().$mslayerctrlpath?>/edit/<?=$item->id?>">Configure</a>
<a href="<?=base_url().$ctrlpath?>/delmslayer/<?=$item->id?>">Remove</a>
<? endif; ?>

<h3>OpenLayers</h3>
<?
$ollayers = $layer->ownOllayer;
if (empty($ollayers)) : ?>
<p><a href="<?=base_url().$ollayerctrlpath?>/edit/new/<?=$layer->id?>">Create layer on OpenLayers</a></p>
<? else : 
$item = reset($ollayers);
?>
<a href="<?=base_url().$ollayerctrlpath?>/edit/<?=$item->id?>">Configure</a>
<a href="<?=base_url().$ctrlpath?>/delollayer/<?=$item->id?>">Remove</a>
<? endif;