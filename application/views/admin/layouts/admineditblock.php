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
<h2>Configure layout block</h2>
<?php if (empty($block)) : ?>
<p>The block does not exists!</p>
<?php else : ?>
    <form method="post" action="<?=base_url($saveaction)?>/<?=$layout->id?>/<?=$block->id?>">
        <label>Name</label>
        <input type="text" name="name" value="<?=$block->name?>" />
        <label>Publish</label>
        <?php if (!in_array($block->publish, array(0, 1))) $block->publish = 0; ?>
        <label for="publish_opt1">
            <input type="radio" name="publish" id="publish_opt1"
                <?php if ($block->publish == 1) :?>checked="checked"<?php endif; ?> value="1" />
            <span>Yes</span>
        </label>
        <label for="publish_opt2">
            <input type="radio" name="publish" id="publish_opt2" 
                <?php if ($block->publish == 0) :?>checked="checked"<?php endif; ?> value="0" />
            <span>No</span>
        </label>
        <label>Publish Order</label>
        <?php if (empty($block->publish)) $block->publish_order = 1; ?>
        <input type="text" name="publish_order" value="<?=$block->publish_order?>" />
        <label>Slot</label>
        <select name="slot_id">
            <?php foreach ($slots as $item) { ?>
            <option value="<?=$item->id?>"<?=$item->id == $slot_id ? 'selected="selected"' : ''?>><?=$item->name?></option>
            <?php } ?>
        </select>
        <label>Module</label>
        <p><?=$block->module->name?></p>
        <?php if ($module_items) : ?>
        <label>Instance</label>
        <select id="module_item" name="module_item">
            <?php foreach ($module_items as $item) { 
                $itemname = empty($item->name) ? $item->title : $item->name;
                ?>
            <option value="<?=$item->id?>" <?=$item->id == $block->item ? 'selected="selected"' : ''?>><?=$itemname?></option>
            <?php } ?>
        </select>
        <?php endif ?>
        <label>Adicional configuration (json)</label>
        <textarea name="config" rows="6" cols="80"><?=$block->config?></textarea>
        <input type="hidden" name="old_slot_id" value="<?=$slot_id?>" />
        <input type="hidden" name="module_id" value="<?=$block->module->id?>" />
        <button type="submit">Save</button>
    </form>
<?php endif; ?>
<a href="<?=base_url()?>admin/adminlayouts/edit/<?=$layout->id?>#editblocks">Back to layout</a>