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
    <p><?=$olmap->map->title?></p>
    <input type="hidden" name="map_id" value="<?=$olmap->map->id?>" />

        <label>Units</label>
        <select name="msunits_id">
            <option value="dd" <?='dd' == $olmap->units ? 'selected="selected"' : ''?>>degrees</option>
            <option value="m" <?='m' == $olmap->units ? 'selected="selected"' : ''?>>meters</option>
            <option value="ft" <?='ft' == $olmap->units ? 'selected="selected"' : ''?>>feets</option>
            <option value="km" <?='km' == $olmap->units ? 'selected="selected"' : ''?>>kilometers</option>
            <option value="mi" <?='miles' == $olmap->units ? 'selected="selected"' : ''?>>mi</option>
            <option value="inches" <?='inches' == $olmap->units ? 'selected="selected"' : ''?>>inches</option>
        </select>

        <label>Projection</label>
        <input type="text" name="projection" value="<?=$olmap->projection?>" />

        <label>Max. extent</label>
        <textarea name="maxextent" cols="60" rows="6"><?=$olmap->maxextent?></textarea>
        
        <label>Restricted extent</label>
        <textarea name="restrictedextent" cols="60" rows="6"><?=$olmap->restrictedextent?></textarea>
        
        <label>Automatic resolution</label>
        <label for="autoresolution_opt1">
            <input type="radio" name="autoresolution" id="autoresolution_opt1"
                <?php if ($olmap->autoresolution == 'true') :?>checked="checked"<?php endif; ?> value="true" />
            <span>Yes</span>
        </label>
        <label for="autoresolution_opt2">
            <input type="radio" name="autoresolution" id="autoresolution_opt2" 
                <?php if ($olmap->autoresolution == 'false') :?>checked="checked"<?php endif; ?> value="false" />
            <span>No</span>
        </label>

        <label>Max. resolution</label>
        <span>Ie. 0.17578125 for EPSG:4326</span>
        <input type="text" name="maxresolution" value="<?=$olmap->maxresolution?>" />

        <label>Number of zoom levels</label>
        <input type="text" name="numzoomlevels" value="<?=$olmap->numzoomlevels?>" />
    
    <button type="submit">Save</button>
</form>