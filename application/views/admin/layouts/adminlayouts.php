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
<h2>Layouts</h2>
<form method="post" action="<?=base_url()?>admin/adminlayouts/save/new">
    <fieldset>
        <legend>New</legend>
        <label>System name</label>
        <input type="text" name="name" value="" /><br />
        <label>PHP View</label>
        <input type="text" name="view" value="" /><br />
        <label>Content</label>
        <textarea name="content" class="wysiwyg"><?=empty($layout->content) ? '' : $layout->content?></textarea>
        <button type="submit">Save</button>
    </fieldset>
</form>
<?php if (empty($items)) : ?>
<p>There are no layouts</p>
<?php else : ?>
<h3>List of layouts</h3>
<form method="post" action="<?=base_url()?>admin/adminlayouts/delete">
    <ul>
        <?php foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url()?>admin/adminlayouts/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->name?></span>
        </li>
        <?php } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<?php endif; ?>