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
<h2>Configure connection type</h2>
<? if (empty($mslayerconntype)) : ?>
<p>The connection type does not exists</p>
<? else : ?>
<form method="post" action="<?=base_url()?>admin/adminmslayerconntype/save/<?=$mslayerconntype->id?>">
    <label>System name</label>
    <input type="text" name="name" value="<?=$mslayerconntype->name?>" />
    <button type="submit">Save</button>
</form>
<?    
endif;
?>