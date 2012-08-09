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
<h2>Configure layout module</h2>
<? if (empty($mod)) : ?>
<p>The module does not exists!</p>
<? else : ?>
<form method="post" action="<?=base_url()?>admin/adminmodules/save/<?=$mod->id?>">
    <label>Name</label>
    <input type="text" name="name" value="<?=$mod->name?>" />
    <label>Class (PHP file)</label>
    <input type="text" name="path" value="<?=$mod->path?>" />
    <label>Instances table</label>
    <input type="text" name="table" value="<?=$mod->table?>" />
    <button type="submit">Save</button>
</form>
<?    
endif;
?>