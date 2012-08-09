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
?><form method="post" action="<?=base_url()?>admin/adminlayouts/edit/<?=empty($layout->id) ? 'new' : $layout->id?>">
    <label>System name</label>
    <input type="text" name="name" value="<?=$layout->name?>" />
    <label>PHP View</label>
    <input type="text" name="view" value="<?=$layout->view?>" />
    <button type="submit">Save</button>
</form>