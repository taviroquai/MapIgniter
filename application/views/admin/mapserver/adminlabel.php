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
<h2>MapServer - Labels</h2>
<?php $this->load->view('admin/mapserver/adminlabelform'); ?>
<?php if (empty($items)) : ?>
<p>There are no labels</p>
<?php else : ?>
<h3>List of labels</h3>
<form method="post" action="<?=base_url().$ctrlpath?>/delete">
    <ul>
        <?php foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url().$ctrlpath?>/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->description?></span>
        </li>
        <?php } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<?php endif; ?>