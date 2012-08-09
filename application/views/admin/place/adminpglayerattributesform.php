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
?><h5>Add Attribute</h5>
<form method="post" action="<?=base_url().$ctrlpath?>/saveattribute/new/<?=$pglayer->id?>">
    
    <label>Name</label>
    <input type="text" name="name" value="" />
    
    <label>Type</label>
    <select name="type">
        <? foreach ($attrtypes as $k => $v) { ?>
        <option value="<?=$k?>"><?=$v?></option>
        <? } ?>
    </select>
    
    <button type="submit">Save</button>
</form>
<h5>Attributes</h5>
<div class="msgs"><p style="background-color: red; color: white;">WARNING: REMOVING ATTRIBUTES MAY CORRUPT APPLICATION</p></div>
<form method="post" action="<?=base_url().$ctrlpath?>/delattribute/<?=$pglayer->id?>">
    <ul>
        <? foreach ($table->attributes as $item => $type) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item?>" />
            <span><?=$item?>: </span><span><?=$type?></span>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
