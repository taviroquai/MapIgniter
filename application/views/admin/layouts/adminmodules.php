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
<h2>Modules</h2>
<form method="post" action="<?=base_url()?>admin/adminmodules/save/new">
    <label>Name</label>
    <input type="text" name="name" value="" />
    <label>Class (PHP file)</label>
    <input type="text" name="path" value="" />
    <label>Instances table</label>
    <input type="text" name="table" value="" />
    <button type="submit">Save</button>
</form>
<? if (empty($items)) : ?>
<p>There are no modules</p>
<? else : ?>
<h3>List of modules</h3>
<form method="post" action="<?=base_url()?>admin/adminmodules/delete">
    <ul>
        <? foreach ($items as $item) { ?>
        <li>
            <table>
                <tr>
                    <td style="width: 100px;">
                        <a href="<?=base_url().$item->previewimg?>">
                            <img style="width: 100px; vertical-align: top;" src="<?=base_url().$item->previewimg?>" />
                        </a>
                    </td>
                    <td>
                        <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                        <a href="<?=base_url()?>admin/adminmodules/edit/<?=$item->id?>">Configure</a>
                        <span><?=$item->name?></span>
                    </td>
                </tr>
            </table>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<? endif; ?>