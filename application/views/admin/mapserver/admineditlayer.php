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
<h2>Layer on Mapserver</h2>
<ul class="tabs">
  <li><a class="active" href="#editmslayer">Configure</a></li>
  <?php if (!empty($mslayer->id)) : ?>
  <li><a href="#mslayer-metadata">Metadata</a></li>
  <li><a href="#mslayer-classes">Feature Classes</a></li>
  <?php if (!empty($datatable)) : ?>
  <li><a href="#mslayer-data">Information</a></li>
  <?php endif; ?>
  <?php endif; ?>
</ul>
<ul class="tabs-content">
  <!-- Give ID that matches HREF of above anchors -->
  <li class="active" id="editmslayer">
<h3>Configure</h3>
<?php if (empty($mslayer)) : ?>
<p>The layer does not exists!</p>
<?php else : ?>
<?php if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<?php $this->load->view('admin/mapserver/adminlayerform'); ?>
<?php endif; ?>
  </li>
  <li id="mslayer-metadata">
<?php if (!empty($mslayer->id)) : ?>
<h3>Metadata</h3>
<form method="post" action="<?=base_url()?>admin/adminmslayer/savemetadata/new/<?=$mslayer->id?>">
    <fieldset>
        <legend>Add Item</legend>
        <div class="accordion">
            <label>Metadata Item</label>
            <select name="msmetadata_id">
            <?php foreach ($msmetadata as $item) { ?>
                <option value="<?=$item->id?>"><?=$item->name?></option>
            <?php } ?>    
            </select>
            <label>Value</label>
            <input type="text" name="value" value="" />
            <button type="submit">Add</button>
        </div>
    </fieldset>
</form>
<?php
$items = $mslayer->ownMslayermd;
if (empty($items)) : ?>
<p>There are no metadata items on this layer</p>
<?php else : ?>
<p><strong>List of metadata items on this layer</strong></p>
<form method="post" action="<?=base_url()?>admin/adminmslayer/delmetadata/<?=$mslayer->id?>">
    <ul>
        <?php foreach ($items as $item) {
        ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <span><?=$item->msmetadata->name?> <?=$item->value?></span>
        </li>
        <?php } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<?php endif; ?>
  </li>
  <li id="mslayer-classes">
<h3>Features class</h3>
<a href="<?=base_url().$msclassctrlpath?>/edit/new/<?=$mslayer->id?>">Create a new class</a>
<?php
$items = $mslayer->ownMsclass;
if (empty($items)) : ?>
<p>There are no classes on this layer</p>
<?php else : ?>
<?php $this->load->view('admin/mapserver/adminclasslist', array('items' => $items)); ?>
<?php endif; ?>
  </li>
  <?php if (!empty($datatable)) : ?>
  <li id="mslayer-data">
    <?php $this->load->view('admin/mapserver/admindatatable', array('datatable' => $datatable)) ?>
  </li>
  <?php endif; ?>
  <?php endif; ?>
</ul>