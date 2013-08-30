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
<h2>Configure metadata item</h2>
<? if (empty($msmetadata)) : ?>
<p>The metadata item does not exists!</p>
<? else : ?>
<form method="post" action="<?=base_url()?>admin/adminmsmetadata/save/<?=$msmetadata->id?>">
    <label>System name</label>
    <input type="text" name="name" value="<?=$msmetadata->name?>" />
    <button type="submit">Save</button>
</form>
<?    
endif;
?>