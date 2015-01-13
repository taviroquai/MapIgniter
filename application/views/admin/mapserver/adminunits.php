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
<h2>MapServer - Units</h2>
<form method="post" action="<?=base_url()?>admin/adminmsunits/save/new">
    <label>New</label>
    <input type="text" name="name" value="" />
    <button type="submit">Save</button>
</form>
<?php if (empty($items)) : ?>
<p>There are no units</p>
<?php else : ?>
<h3>List of units</h3>
<form method="post" action="<?=base_url()?>admin/adminmsunits/delete">
    <ul>
        <?php foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url()?>admin/adminmsunits/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->name?></span>
        </li>
        <?php } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<?php endif; ?>