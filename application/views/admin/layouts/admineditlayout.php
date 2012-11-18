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
$slots = $layout->ownLslot;
?>
<h2>Layout</h2>
<ul class="tabs">
  <li><a class="active" href="#editlayout">Configure</a></li>
  <? if (!empty($layout->id)) : ?>
  <li><a href="#editslots">Slots</a></li>
  <? if (!empty($slots)) : ?>
  <li><a href="#editblocks">Blocks</a></li>
  <? endif; ?>
  <? endif; ?>
</ul>
<ul class="tabs-content">
    <li class="active" id="editlayout">
        <h3>Configure</h3>
        <? if (empty($layout)) : ?>
        <p>The layout does not exists!</p>
        <? else : ?>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/save/<?=$layout->id?>">
            <label>System name</label>
            <input type="text" name="name" value="<?=$layout->name?>" />
            <label>PHP View</label>
            <input type="text" name="view" value="<?=$layout->view?>" />
            <button type="submit">Save</button>
        </form>
        <? endif; ?>
    </li>
    <? if (!empty($layout->id)) : ?>
    <li id="editslots">
        <h3>Create layout slot</h3>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/saveslot/<?=$layout->id?>/new">
            <label>System name</label>
            <input type="text" name="name" value="novo" />
            <input type="hidden" name="layout_id" value="<?=$layout->id?>" />
            <button type="submit">Save</button>
        </form>
        <h3>Layout slots list</h3>
        <? if (empty($slots)) : ?>
        <p>There are no slots registered on this layout</p>
        <? else : ?>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/deleteslot">
            <ul>
                <? foreach ($slots as $item) { ?>
                <li>
                    <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                    <a href="<?=base_url()?>admin/adminlayouts/editslot/<?=$layout->id?>/<?=$item->id?>">Configurar</a>
                    <span><?=$item->name?></span>
                </li>
                <? } ?>
            </ul>
            <input type="hidden" name="layout_id" value="<?=$layout->id?>" />
            <button type="submit">Remove selected</button>
        </form>
        <? endif; ?>
    </li>
    <? if (!empty($slots)) : ?>
    <li id="editblocks">
        <h3>Add block to slot</h3>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/saveblock/<?=$layout->id?>/new">
            <label>Instance name</label>
            <input type="text" name="name" value="novo" />
            
            <label>Slot</label>
            <select name="slot_id">
                <? foreach ($slots as $item) { ?>
                <option value="<?=$item->id?>"><?=$item->name?></option>
                <? } ?>
            </select>
            
            <label>Module</label>
            <select name="module_id">
                <? foreach ($modules as $module) { ?>
                <option value="<?=$module->id?>"><?=$module->name?></option>
                <? } ?>
            </select>
            <button type="submit">Save</button>
        </form>
        <h3>List of blocks on this layout </h3>
        <? $total_blocks = 0 ?>
        <? if (!empty($slots)) : ?>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/deleteblock">
            <table class="blocklist">
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Module</th>
                    <th>Slot</th>
                    <th>Order</th>
                    <th>Publish</th>
                </tr>
            <? foreach ($slots as $slot) {
            $blocks = $slot->sharedLblock;
            $total_blocks += count($blocks);
            if (!empty($blocks)) : ?>
                <? foreach ($blocks as $block) { ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selected[]" value="<?=$block->id?>" />
                    </td>
                    <td><a href="<?=base_url()?>admin/adminlayouts/editblock/<?=$layout->id?>/<?=$block->id?>"><?=$block->name?></a></td>
                    <td><?=$block->module->name?></td>
                    <td><?=$slot->name?></td>
                    <td><?=$block->publish_order?></td>
                    <td>
                        <? if ($block->publish) : ?>
                        <img src="<?=base_url()?>web/images/icons/png/16x16/check.png" title="published" alt="published" />
                        <? else: ?>
                        <img src="<?=base_url()?>web/images/icons/png/16x16/no.png" title="not published" alt="not published" />
                        <? endif; ?>
                    </td>
                </tr>
                <? } ?>
            <? endif;
            } ?>
            </table>
            <input type="hidden" name="layout_id" value="<?=$layout->id?>" />
            <? if (!empty($total_blocks)) : ?>
            <button type="submit">Remove selected</button>
            <? else: ?>
            <p>There are no blocks on this layout</p>
            <? endif; ?>
        </form>
        <? endif; ?>
    </li>
    <? endif; ?>
    <? endif; ?>
</ul>