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
<h2>Configure menu item</h2>
<?php if (!$menu = $menuitem->modmenu) : ?>
<p>The menu item dows not exists!</p>
<?php else : ?>
<?php if (empty($menuitem->id)) $itemid = 'new';
    else $itemid = $menuitem->id;
?>
<h3>New menu item</h3>
<form method="post" action="<?=base_url()?>admin/adminmenus/saveitem/<?=$menuitem->id?>">
    <label>Label</label>
    <input type="text" name="label" value="<?=$menuitem->label?>" /><br />
    <label>Path</label>
    <input type="text" name="href" value="<?=$menuitem->href?>" /><br />
    <label>Internal</label>
    <input type="checkbox" name="internal" value="1" <?=empty($menuitem->internal) ? '' : ' checked="checked"'?> /><br />
    <label>N. of order</label>
    <input type="text" name="listorder" value="<?=$menuitem->listorder?>" /><br />
    <input type="hidden" name="modmenu_id" value="<?=$menuitem->modmenu->id?>" />
    <button type="submit">Save</button>
</form>
<?php endif;