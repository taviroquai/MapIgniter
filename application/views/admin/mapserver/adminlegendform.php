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
    
    <label>Map</label>
    <p><?=$mslegend->msmapfile->map->title?></p>
    <input type="hidden" name="msmapfile_id" value="<?=$mslegend->msmapfile->id?>" />
    
    <label>Status</label>
    <label for="status_opt1">
        <input type="radio" name="status" id="status_opt1"
            <?php if ($mslegend->status == 'on') :?>checked="checked"<?php endif; ?> value="on" />
        <span>On</span>
    </label>
    <label for="status_opt2">
        <input type="radio" name="status" id="status_opt2" 
            <?php if ($mslegend->status == 'off') :?>checked="checked"<?php endif; ?> value="off" />
        <span>Off</span>
    </label>
    
    <fieldset>
        <legend>Apearance</legend>
        <div class="accordion">
            
            <label for="mslabel">Label</label>
            <select name="mslabel_id">
                <?php foreach ($mslabels as $item) {?>
                <option value="<?=$item->id?>"><?=substr($item->description, 0, 40)?></option>
                <?php } ?>
            </select>

            <label for="position">Text position</label>
            <select name="position">
                <option <?=$mslegend->position = 'ul' ? 'checked="checked"' : ''?>value="ul">Upper left</option>
                <option <?=$mslegend->position = 'uc' ? 'checked="checked"' : ''?>value="uc">Upper center</option>
                <option <?=$mslegend->position = 'ur' ? 'checked="checked"' : ''?>value="ur">Upper right</option>
                <option <?=$mslegend->position = 'll' ? 'checked="checked"' : ''?>value="ll">Lower left</option>
                <option <?=$mslegend->position = 'lc' ? 'checked="checked"' : ''?>value="lc">Lower center</option>
                <option <?=$mslegend->position = 'lr' ? 'checked="checked"' : ''?>value="lr">Lower right</option>
            </select>
            
            <label>Template file</label>
            <input type="text" name="template" value="<?=$mslegend->template?>" />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Dimensions and measures</legend>
        <div class="accordion">

            <label>Symbol size (x y)</label>
            <input type="text" name="keysize" value="<?=$mslegend->keysize?>" />
            
            <label>Symbol spacing (x y)</label>
            <input type="text" name="keyspacing" value="<?=$mslegend->keyspacing?>" />

        </div>
    </fieldset>
    
    <fieldset>
        <legend>Colors</legend>
        <div class="accordion">

            <label>Background color</label>
            <span id="imagecolor_palete"></span>
            <input type="text" name="imagecolor" value="<?=$mslegend->imagecolor?>" />

            <label>Outline color</label>
            <span id="outlinecolor_palete"></span>
            <input type="text" name="outlinecolor" value="<?=$mslegend->outlinecolor?>" />

        </div>
    </fieldset>
    
    <fieldset>
        <legend>Extras</legend>
        <div class="accordion">

            <label>Render only after cache</label>
            <label for="postlabelcache_opt1">
                <input type="radio" name="postlabelcache" id="postlabelcache_opt1"
                    <?php if ($mslegend->postlabelcache == 'true') :?>checked="checked"<?php endif; ?> value="true" />
                <span>Yes</span>
            </label>
            <label for="postlabelcache_opt2">
                <input type="radio" name="postlabelcache" id="postlabelcache_opt2" 
                    <?php if ($mslegend->postlabelcache == 'false') :?>checked="checked"<?php endif; ?> value="false" />
                <span>No</span>
            </label>
        </div>
    </fieldset>
    
    <button type="submit">Save</button>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        initInputColorArray(['imagecolor', 'outlinecolor']);
        $('div.accordion').hide();
        $('form legend').click(function() {
            $(this).parent().find('div.accordion').slideToggle("slow");
	});
    });
</script>