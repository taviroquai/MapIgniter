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
    
    <label>Title</label>
    <p><?=$msmapfile->map->title?></p>
    <input type="hidden" name="map_id" value="<?=$msmapfile->map->id?>" />
    
    <label>Debug</label>
    <label for="debug_opt1">
        <input type="radio" name="debug" id="debug_opt1"
            <? if ($msmapfile->debug == '3') :?>checked="checked"<? endif; ?> value="3" />
        <span>On</span>
    </label>
    <label for="debug_opt2">
        <input type="radio" name="debug" id="debug_opt2" 
            <? if ($msmapfile->debug == 'off') :?>checked="checked"<? endif; ?> value="off" />
        <span>Off</span>
    </label>
    
    <fieldset>
        <legend>Dimensions and measures</legend>
        <div class="accordion">
            <label>Units</label>
            <select name="msunits_id">
            <? foreach ($msunits as $item) { ?>
                <option value="<?=$item->id?>" <?=$item->id == $msmapfile->msunits->id ? 'selected="selected"' : ''?>><?=$item->name?></option>
            <? } ?>    
            </select>
            
            <label>Projection</label>
            <textarea name="projection" cols="60" rows="13"><?=$msmapfile->projection?></textarea>

            <label>Extent</label>
            <input type="text" name="extent" value="<?=$msmapfile->extent?>" />
            
            <label>Default Width (sizex)</label>
            <input type="text" name="sizex" value="<?=$msmapfile->sizex?>" />
            
            <label>Default height (sizey)</label>
            <input type="text" name="sizey" value="<?=$msmapfile->sizey?>" />

        </div>
    </fieldset>
    
    <fieldset>
        <legend>Appearance</legend>
        <div class="accordion">
            <label>Fontset file&nbsp;
                <a class="linkexplorer fancybox.ajax" title="Explorer" href="<?=base_url().$dataexplorerctrlpath?>?return=msmapfontset"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="explorador" title="Explorer" /></a>
            </label>
            <input id="msmapfontset" type="text" name="fontset" value="<?=$msmapfile->fontset?>" />
            
            <label>Symbolset file&nbsp;
                <a class="linkexplorer fancybox.ajax" title="Explorer" href="<?=base_url().$dataexplorerctrlpath?>?return=msmapsymbolset"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="explorador" title="Explorer" /></a>
            </label>
            <input id="msmapsymbolset" type="text" name="symbolset" value="<?=$msmapfile->symbolset?>" />

            <label>Image type</label>
            <select name="mslayertype_id">
                <option value="PNG" <?='PNG' == $msmapfile->imagetype ? 'selected="selected"' : ''?>>PNG</option>
            </select>
            
            <label>Background color</label>
            <span id="imagecolor_palete"></span>
            <input type="text" name="imagecolor" value="<?=$msmapfile->imagecolor?>" />

        </div>
    </fieldset>
    
    <button type="submit">Save</button>
</form>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        initInputColorArray(['imagecolor']);
        $('div.accordion').hide();
        $('form legend').click(function() {
            $(this).parent().find('div.accordion').slideToggle("slow");
	});
        $("a.linkexplorer").fancybox({
            'height': 600,
            'autoSize': false,
            'width': 800
        });
    });
</script>