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
?><form method="post" action="<?=base_url().$ctrlpath?><?=$action?>">
    
    <label>Title</label>
    <p><?=$gemap->map->title?></p>
    <input type="hidden" name="map_id" value="<?=$gemap->map->id?>" />        
    
    <button type="submit">Save</button>
</form>