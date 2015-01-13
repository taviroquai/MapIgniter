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
<h3>From Shapefile</h3>
<?php echo form_open_multipart(base_url().$ctrlpath.$action)?>

    <label>Create postgis table name</label>
    <input type="text" name="new_pgplacetype" value="import<?=rand(0, 999)?>" />
    
    <label>Or overwrite existing table (optional)</label>
    <select name="pgplacetype">
        <option value="new_pgplacetype">New table...</option>
        <?php if (!empty($tables)) : ?>
            <?php foreach ($tables as $item) { ?>
            <option value="<?=$item->name?>"><?=$item->name?></option>
            <?php } ?>
        <?php endif; ?>
    </select>
    
    <label>SRID</label>
    <p style="background-color: red; color: white;">WARNING: WRONG SRID (ALSO IN SHAPEFILE) MAY RESULT IN BROKEN LAYER</p>
    <select name="srid">
    <?php foreach ($srid_list as $item) { ?>
        <option value="<?=$item['srid']?>" <?=$item['srid'] == '3857' ? 'selected="selected"': ''?>><?=$item['auth_name'].':'.$item['srid']?></option>
    <?php } ?>    
    </select>
    
    <label>shp2pgsql options (optional)</label>
    <input type="text" name="options" value="-d -I -S -g the_geom" />

    <label>Choose shapefile (zip)</label>
    <span><small>Note: a .zip file including the files .dbf, .prj, .shp and .shx</small></span><br />
    <input type="file" name="userfile" size="20" /><br />
    
    <button type="submit">Import</button>
</form>