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
$slots = $layout->ownLslot;
?>
<h2>Layout</h2>
<ul class="tabs">
  <li><a class="active" href="#editlayout">Configure</a></li>
  <?php if (!empty($layout->id)) : ?>
  <li><a href="#editslots">Slots</a></li>
  <?php if (!empty($slots)) : ?>
  <li><a href="#editblocks">Blocks</a></li>
  <?php endif; ?>
  <?php endif; ?>
</ul>
<ul class="tabs-content">
    <li class="active" id="editlayout">
        <h3>Configure</h3>
        <?php if (empty($layout)) : ?>
        <p>The layout does not exists!</p>
        <?php else : ?>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/save/<?=$layout->id?>">
            <label>System name</label>
            <input type="text" name="name" value="<?=$layout->name?>" />
            <label>PHP View</label>
            <input type="text" name="view" value="<?=$layout->view?>" />
            <label>Content</label>
            <textarea name="content" class="wysiwyg"><?=empty($layout->content) ? '' : $layout->content?></textarea>
            <button type="submit">Save</button>
        </form>
        <?php endif; ?>
    </li>
    <?php if (!empty($layout->id)) : ?>
    <li id="editslots">
        <h3>Create layout slot</h3>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/saveslot/<?=$layout->id?>/new">
            <label>System name</label>
            <input type="text" name="name" value="novo" />
            <input type="hidden" name="layout_id" value="<?=$layout->id?>" />
            <button type="submit">Save</button>
        </form>
        <h3>Layout slots list</h3>
        <?php if (empty($slots)) : ?>
        <p>There are no slots registered on this layout</p>
        <?php else : ?>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/deleteslot">
            <ul>
                <?php foreach ($slots as $item) { ?>
                <li>
                    <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                    <a href="<?=base_url()?>admin/adminlayouts/editslot/<?=$layout->id?>/<?=$item->id?>">Configurar</a>
                    <span><?=$item->name?></span>
                </li>
                <?php } ?>
            </ul>
            <input type="hidden" name="layout_id" value="<?=$layout->id?>" />
            <button type="submit">Remove selected</button>
        </form>
        <?php endif; ?>
    </li>
    <?php if (!empty($slots)) : ?>
    <li id="editblocks">
        <h3>Create a new block</h3>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/createblock/<?=$layout->id?>">
            <label>Instance name</label>
            <input type="text" name="name" value="novo" />
            
            <label>Slot</label>
            <select name="slot_id">
                <?php foreach ($slots as $item) { ?>
                <option value="<?=$item->id?>"><?=$item->name?></option>
                <?php } ?>
            </select>
            
            <label>Module</label>
            <select name="module_id">
                <?php foreach ($modules as $module) { ?>
                <option value="<?=$module->id?>"><?=$module->name?></option>
                <?php } ?>
            </select>
            <button type="submit">Save</button>
        </form>
        <h3>List of blocks on this layout </h3>
        <?php $total_blocks = 0 ?>
        <?php if (!empty($slots)) : ?>
        <form method="post" action="<?=base_url()?>admin/adminlayouts/deleteblock">
            <?php foreach ($slots as $slot) {
            $blocks = $slot->sharedLblock;
            $total_blocks += count($blocks);
            if (!empty($blocks)) : ?>
            <br /><span><?=$slot->name?></span>
            <table class="blocklist">
                <thead>
                    <tr>
                        <th style="width: 20px"></th>
                        <th>Name</th>
                        <th style="width: 200px">Module</th>
                        <th style="width: 40px">Order</th>
                        <th style="width: 40px">Publish</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($blocks as $block) { ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selected[]" value="<?=$block->id?>" />
                    </td>
                    <td><a href="<?=base_url($block->editpath)?>/<?=$layout->id?>/<?=$block->id?>"><?=$block->name?></a></td>
                    <td><?=$block->module->name?></td>
                    <td><?=$block->publish_order?></td>
                    <td>
                        <?php if ($block->publish) : ?>
                        <img src="<?=base_url()?>web/images/icons/png/16x16/check.png" title="published" alt="published" />
                        <?php else: ?>
                        <img src="<?=base_url()?>web/images/icons/png/16x16/no.png" title="not published" alt="not published" />
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
                </table>
            <?php endif;
            } ?>
            <input type="hidden" name="layout_id" value="<?=$layout->id?>" />
            <?php if (!empty($total_blocks)) : ?>
            <button type="submit">Remove selected</button>
            <?php else: ?>
            <p>There are no blocks on this layout</p>
            <?php endif; ?>
        </form>
        <?php endif; ?>
    </li>
    <?php endif; ?>
    <?php endif; ?>
</ul>