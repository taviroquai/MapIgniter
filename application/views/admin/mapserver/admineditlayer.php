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
<h2>Layer on Mapserver</h2>
<ul class="tabs">
  <li><a class="active" href="#editmslayer">Configure</a></li>
  <? if (!empty($mslayer->id)) : ?>
  <li><a href="#mslayer-metadata">Metadata</a></li>
  <li><a href="#mslayer-classes">Feature Classes</a></li>
  <? if (!empty($datatable)) : ?>
  <li><a href="#mslayer-data">Information</a></li>
  <? endif; ?>
  <? endif; ?>
</ul>
<ul class="tabs-content">
  <!-- Give ID that matches HREF of above anchors -->
  <li class="active" id="editmslayer">
<h3>Configure</h3>
<? if (empty($mslayer)) : ?>
<p>The layer does not exists!</p>
<? else : ?>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<? $this->load->view('admin/mapserver/adminlayerform'); ?>
<? endif; ?>
  </li>
  <li id="mslayer-metadata">
<? if (!empty($mslayer->id)) : ?>
<h3>Metadata</h3>
<form method="post" action="<?=base_url()?>admin/adminmslayer/savemetadata/new/<?=$mslayer->id?>">
    <fieldset>
        <legend>Add Item</legend>
        <div class="accordion">
            <label>Metadata Item</label>
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
$items = $mslayer->ownMslayermd;
if (empty($items)) : ?>
<p>There are no metadata items on this layer</p>
<? else : ?>
<p><strong>List of metadata items on this layer</strong></p>
<form method="post" action="<?=base_url()?>admin/adminmslayer/delmetadata/<?=$mslayer->id?>">
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
  <li id="mslayer-classes">
<h3>Features class</h3>
<a href="<?=base_url().$msclassctrlpath?>/edit/new/<?=$mslayer->id?>">Create a new class</a>
<?
$items = $mslayer->ownMsclass;
if (empty($items)) : ?>
<p>There are no classes on this layer</p>
<? else : ?>
<? $this->load->view('admin/mapserver/adminclasslist', array('items' => $items)); ?>
<? endif; ?>
  </li>
  <? if (!empty($datatable)) : ?>
  <li id="mslayer-data">
    <? $this->load->view('admin/mapserver/admindatatable', array('datatable' => $datatable)) ?>
  </li>
  <? endif; ?>
  <? endif; ?>
</ul>