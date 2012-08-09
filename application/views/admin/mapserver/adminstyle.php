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
<h2>MapServer - Styles</h2>
<? $this->load->view('admin/mapserver/adminstyleform'); ?>
<? if (empty($items)) : ?>
<p>There are no styles!</p>
<? else : ?>
<h3>List of styles</h3>
<form method="post" action="<?=base_url()?>admin/adminmsstyle/delete">
    <ul>
        <? foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url()?>admin/adminmsstyle/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->description?></span>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<? endif; ?>