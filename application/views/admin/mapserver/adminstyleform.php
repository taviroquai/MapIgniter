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
    <label>Description</label>
    <textarea name="description"><?=$msstyle->description?></textarea>

    <fieldset>
        <legend>Appearance</legend>
        <div class="accordion">
            <label>Symbol&nbsp;
                <a class="linkexplorer fancybox.ajax" title="Explorer" href="<?=base_url().$dataexplorerctrlpath?>?return=msstylesymbol&replace=1&list=<?=dirname($msstyle->symbol)?>/"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="explorador" title="Explorer" /></a>
            </label>
            <input id="msstylesymbol"type="text" name="symbol" value="<?=$msstyle->symbol?>" />
            <? if (!empty($sym_preview)) : ?>
            <img src="<?=$sym_preview?>" alt="Symbol Preview" />
            <? endif; ?>
            
            <label>Pattern</label>
            <input type="text" name="pattern" value="<?=$msstyle->pattern?>" />
        </div>
    </fieldset>
    <fieldset>
        <legend>Dimensions and measures</legend>
        <div class="accordion">
            
            <label>Size</label>
            <input type="text" name="size" value="<?=$msstyle->size?>" />
            
            <label>Width</label>
            <input type="text" name="width" value="<?=$msstyle->width?>" />
            
            <label>Max. size</label>
            <input type="text" name="maxsize" value="<?=$msstyle->maxsize?>" />
            
            <label>Max. width</label>
            <input type="text" name="maxwidth" value="<?=$msstyle->maxwidth?>" />
            
            <label>Offset (x y)</label>
            <input type="text" name="offset" value="<?=$msstyle->offset?>" />
        </div>
    </fieldset>
    <fieldset>
        <legend>Colors</legend>
        <div class="accordion">
            
            <label>Opacity</label>
            <input type="text" name="opacity" value="<?=$msstyle->opacity?>" />
            
            <label>Color</label>
            <span id="color_palete"></span>
            <input type="text" name="color" value="<?=$msstyle->color?>" />

            <label>Background color</label>
            <span id="bgcolor_palete"></span>
            <input type="text" name="bgcolor" value="<?=$msstyle->bgcolor?>" />

            <label>Outline color</label>
            <span id="outlinecolor_palete"></span>
            <input type="text" name="outlinecolor" value="<?=$msstyle->outlinecolor?>" />
        </div>
    </fieldset>
    <fieldset>
        <legend>Repetition</legend>
        <div class="accordion">
            <label>Symbols gap</label>
            <input type="text" name="gap" value="<?=$msstyle->gap?>" />
            
            <label>Geometry transform</label>
            <input type="text" name="geomtransform" value="<?=$msstyle->geomtransform?>" />
            
            <label>Line cap</label>
            <label for="linecap_opt1">
                <input type="radio" name="linecap" id="linecap_opt1" 
                       <? if ($msstyle->linecap == 'round') :?>checked="checked"<? endif; ?> value="round" />
                <span>Round</span>
            </label>
            <label for="linecap_opt2">
                <input type="radio" name="linecap" id="linecap_opt2"
                       <? if ($msstyle->linecap == 'butt') :?>checked="checked"<? endif; ?> value="butt" />
                <span>Butt</span>
            </label>
            <label for="linecap_opt3">
                <input type="radio" name="linecap" id="linecap_opt3"
                       <? if ($msstyle->linecap == 'square') :?>checked="checked"<? endif; ?> value="square" />
                <span>Square</span>
            </label>
            <label>Line join</label>
            <label for="linejoin_opt1">
                <input type="radio" name="linejoin" id="linejoin_opt1"
                       <? if ($msstyle->linejoin == 'round') :?>checked="checked"<? endif; ?> value="round" />
                <span>Round</span>
            </label>
            <label for="linejoin_opt2">
                <input type="radio" name="linejoin" id="linejoin_opt2"
                       <? if ($msstyle->linejoin == 'miter') :?>checked="checked"<? endif; ?> value="miter" />
                <span>Miter</span>
            </label>
            <label for="linejoin_opt3">
                <input type="radio" name="linejoin" id="linejoin_opt3"
                       <? if ($msstyle->linejoin == 'bevel') :?>checked="checked"<? endif; ?> value="bevel" />
                <span>Bevel</span>
            </label>
            <label>Line join max. size</label>
            <input type="text" name="linejoinmaxsize" value="<?=$msstyle->linejoinmaxsize?>" />
        </div>
    </fieldset>
    <fieldset>
        <legend>Extras</legend>
        <div class="accordion">
            <label>Angle</label>
            <input type="text" name="angle" value="<?=$msstyle->angle?>" />
            <label>Anti-alias (gif)</label>
            <label for="antialias_opt1">
                <input type="radio" name="antialias" id="antialias_opt1"
                    <? if ($msstyle->antialias == 'true') :?>checked="checked"<? endif; ?> value="true" />
                <span>Yes</span>
            </label>
            <label for="antialias_opt2">
                <input type="radio" name="antialias" id="antialias_opt2" 
                    <? if ($msstyle->antialias == 'false') :?>checked="checked"<? endif; ?> value="false" />
                <span>No</span>
            </label>
        </div>
    </fieldset>
    <button type="submit">Save</button>
</form>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        initInputColorArray(['color', 'bgcolor', 'outlinecolor']);
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