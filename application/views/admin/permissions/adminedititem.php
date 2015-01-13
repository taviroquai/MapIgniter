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
<h2>Configure Permission</h2>
<?php if (empty($permission)) : ?>
<p>The permission does not exists!</p>
<?php else : ?>
    <form method="post" action="<?=base_url()?>admin/adminpermissions/saveitem/<?=$permission->id?>">
        <label>Action</label>
        <select name="action">
            <option <?=$permission->action == 0 ? 'selected="selected"' : ''?>value="deny">Deny</option>
            <option <?=$permission->action == 1 ? 'selected="selected"' : ''?>value="allow">Allow</option>
        </select><br />
        <label>Expires</label>
        <input type="text" name="expire" value="<?=empty($permission->expire) ? 0 : date('Y-m-d', $permission->expire)?>" /><br />
        <button type="submit">Save</button>
    </form>
<?php endif; ?>