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
?><form method="post" action="<?=base_url().$ctrlpath.$action?>">
    
    <label>Layer title</label>
    <p><?=$gelayer->layer->title?></p>
    <input type="hidden" name="layer_id" value="<?=$gelayer->layer->id?>" />
    
    <label>Layer type</label>
    <select name="gelayertype_id">
    <? foreach ($gelayertypes as $item) { ?>
        <option value="<?=$item->id?>" <?=$item->id == $gelayer->gelayertype->id ? 'selected="selected"' : ''?>><?=$item->type?></option>
    <? } ?>    
    </select>
    
    <button type="submit">Save</button>
</form>