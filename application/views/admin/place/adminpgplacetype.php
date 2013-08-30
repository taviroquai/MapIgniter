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
<h2>Place Type</h2>
<? if (empty($items)) : ?>
<p>There are place types</p>
<? else : ?>
<h3>Create a new place type</h3>
<? $this->load->view('admin/place/adminpgplacetypeform'); ?>
<h3>List of place types</h3>
<form method="post" action="<?=base_url().$ctrlpath?>/delete">
    <ul>
        <? foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->name?>" />
            <a href="<?=base_url().$ctrlpath?>/edit/<?=$item->name?>">Configure</a>
            <span><?=$item->name?></span>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<? endif; ?>