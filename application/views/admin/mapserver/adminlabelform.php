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
    <label>Description</label>
    <textarea name="description"><?=$mslabel->description?></textarea>
    
    <fieldset>
        <legend>Appearance</legend>
        <div class="accordion">            
            <label>Font</label>
            <input type="text" name="font" value="<?=$mslabel->font?>" />
            
            <label>Encoding</label>
            <input type="text" name="encoding" value="<?=$mslabel->encoding?>" />
            
            <label>Type of Font</label>
            <label for="type_opt1">
                <input type="radio" name="type" id="type_opt1"
                    <?php if ($mslabel->type == 'bitmap') :?>checked="checked"<?php endif; ?> value="bitmap" />
                <span>Bitmap</span>
            </label>
            <label for="type_opt2">
                <input type="radio" name="type" id="type_opt2" 
                    <?php if ($mslabel->type == 'truetype') :?>checked="checked"<?php endif; ?> value="truetype" />
                <span>TrueType</span>
            </label>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Dimensions and measures</legend>
        <div class="accordion">
            
            <label>Size</label>
            <input type="text" name="size" value="<?=$mslabel->size?>" />
            
            <label>Min. size</label>
            <input type="text" name="minsize" value="<?=$mslabel->minsize?>" />
    
            <label>Max. size</label>
            <input type="text" name="maxsize" value="<?=$mslabel->maxsize?>" />
            
            <label>Outline width</label>
            <input type="text" name="outlinewidth" value="<?=$mslabel->outlinewidth?>" />
            
            <label>Buffer around text</label>
            <input type="text" name="buffer" value="<?=$mslabel->buffer?>" />
            
            <label>Text shadow size</label>
            <input type="text" name="shadowsize" value="<?=$mslabel->shadowsize?>" />
            
            <label>Máx. length for a line</label>
            <input type="text" name="maxlength" value="<?=$mslabel->maxlength?>" />
            
            <label>Min. text distance</label>
            <input type="text" name="mindistance" value="<?=$mslabel->mindistance?>" />

            <label>Min. feature size for labels</label>
            <input type="text" name="minfeaturesize" value="<?=$mslabel->minfeaturesize?>" />

            <label>Offset (x y)</label>
            <input type="text" name="offset" value="<?=$mslabel->offset?>" />
            
            <label>Text wrap character</label>
            <input type="text" name="wrap" value="<?=$mslabel->wrap?>" />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Colors</legend>
        <div class="accordion">
            
            <label>Color</label>
            <span id="color_palete"></span>
            <input type="text" name="color" value="<?=$mslabel->color?>" />

            <label>Background color</label>
            <span id="bgcolor_palete"></span>
            <input type="text" name="bgcolor" value="<?=$mslabel->bgcolor?>" />

            <label>Outline color</label>
            <span id="outlinecolor_palete"></span>
            <input type="text" name="outlinecolor" value="<?=$mslabel->outlinecolor?>" />

            <label>Shadow color</label>
            <span id="shadowcolor_palete"></span>
            <input type="text" name="shadowcolor" value="<?=$mslabel->shadowcolor?>" />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Position</legend>
        <div class="accordion">
            
            <label>Alignment</label>
            <label for="align_opt1">
                <input type="radio" name="align" id="align_opt1" 
                       <?php if ($mslabel->align == 'left') :?>checked="checked"<?php endif; ?> value="left" />
                <span>Left</span>
            </label>
            <label for="align_opt2">
                <input type="radio" name="align" id="align_opt2"
                       <?php if ($mslabel->align == 'center') :?>checked="checked"<?php endif; ?> value="center" />
                <span>Center</span>
            </label>
            <label for="align_opt3">
                <input type="radio" name="align" id="align_opt3"
                       <?php if ($mslabel->align == 'right') :?>checked="checked"<?php endif; ?> value="right" />
                <span>Right</span>
            </label>

            <label for="position">Relative position to feature (corner)</label>
            <select id="position">
                <option <?=$mslabel->position = 'auto' ? 'checked="checked"' : ''?> value="auto">Automatic</option>
                <option <?=$mslabel->position = 'ul' ? 'checked="checked"' : ''?>value="ul">Upper left</option>
                <option <?=$mslabel->position = 'uc' ? 'checked="checked"' : ''?>value="uc">Upper center</option>
                <option <?=$mslabel->position = 'ur' ? 'checked="checked"' : ''?>value="ur">Upper right</option>
                <option <?=$mslabel->position = 'cl' ? 'checked="checked"' : ''?>value="cl">Center left</option>
                <option <?=$mslabel->position = 'cc' ? 'checked="checked"' : ''?>value="cc">Center center</option>
                <option <?=$mslabel->position = 'cr' ? 'checked="checked"' : ''?>value="cr">Center right</option>
                <option <?=$mslabel->position = 'll' ? 'checked="checked"' : ''?>value="ll">Lower left</option>
                <option <?=$mslabel->position = 'lc' ? 'checked="checked"' : ''?>value="lc">Lower center</option>
                <option <?=$mslabel->position = 'lr' ? 'checked="checked"' : ''?>value="lr">lower right</option>
            </select>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Extras</legend>
        <div class="accordion">
            
            <label>Angle</label>
            <input type="text" name="angle" value="<?=$mslabel->angle?>" />

            <label>Anti-alias (gif)</label>
            <label for="antialias_opt1">
                <input type="radio" name="antialias" id="antialias_opt1"
                    <?php if ($mslabel->antialias == 'true') :?>checked="checked"<?php endif; ?> value="true" />
                <span>Yes</span>
            </label>
            <label for="antialias_opt2">
                <input type="radio" name="antialias" id="antialias_opt2" 
                    <?php if ($mslabel->antialias == 'false') :?>checked="checked"<?php endif; ?> value="false" />
                <span>No</span>
            </label>
            
            <label>Force</label>
            <input type="text" name="force" value="<?=$mslabel->force?>" />

            <label>Max. angle to allow features overlap</label>
            <input type="text" name="maxoverlapangle" value="<?=$mslabel->maxoverlapangle?>" />

            <label>Allow partial labels</label>
            <input type="text" name="partials" value="<?=$mslabel->partials?>" />
            
            <label>Labels overlapping priority</label>
            <input type="text" name="priority" value="<?=$mslabel->priority?>" />

            <label>Repetition distance</label>
            <input type="text" name="repeatdistance" value="<?=$mslabel->repeatdistance?>" />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Style</legend>
        <div class="accordion">
            <label>Satyle</label>
            <input type="text" name="style_id" value="<?=$mslabel->style_id?>" />
        </div>
    </fieldset>
    
    <button type="submit">Save</button>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        initInputColorArray(['color', 'bgcolor', 'outlinecolor', 'shadowcolor']);
        $('div.accordion').hide();
        $('form legend').click(function() {
            $(this).parent().find('div.accordion').slideToggle("slow");
	});
    });
</script>