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
?><form method="post" action="<?=base_url().$ctrlpath.$action?>">
    
    <label>Layer title</label>
    <p><?=$ollayer->layer->title?></p>
    <input type="hidden" name="layer_id" value="<?=$ollayer->layer->id?>" />
    
    <label>Layer type</label>
    <select name="ollayertype_id">
    <? foreach ($ollayertypes as $item) { ?>
        <option value="<?=$item->id?>" <?=$item->id == $ollayer->ollayertype->id ? 'selected="selected"' : ''?>><?=$item->type?></option>
    <? } ?>    
    </select>
    
    <? if ($ollayer->ollayertype->id == 4) : ?>
    <label>MapServer Map</label>
    <select name="url">
        <option value="">Choose...</option>
        <? foreach ($maps as $item) { ?>
        <option value="<?=$item->alias?>" <?=$item->alias == $ollayer->url ? 'selected="selected"' : ''?>><?=$item->title?></option>
        <? } ?>
    </select>
    <? else : ?>
    <label>URL</label>
    <input type="text" name="url" value="<?=$ollayer->url?>" />
    <? endif; ?>
    
    <label>OpenLayers options (JSON)</label>
    <textarea name="options" cols="60" rows="6"><?=$ollayer->options?></textarea>
    
    <label>Layer vendor options (JSON)</label>
    <textarea name="vendorparams" cols="60" rows="6"><?=$ollayer->vendorparams?></textarea>
    
    <label>Layer information website (optional)</label>
    <textarea name="informationurl" cols="60" rows="6"><?=$ollayer->informationurl?></textarea>
    
    <button type="submit">Save</button>
</form>