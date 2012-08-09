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
    
    <label>Name</label>
    <input type="text" name="name" value="<?=$msclass->name?>" />

    <label>Status</label>
    <label for="status_opt1">
        <input type="radio" name="status" id="status_opt1"
            <? if ($msclass->status == 'on') :?>checked="checked"<? endif; ?> value="on" />
        <span>On</span>
    </label>
    <label for="status_opt2">
        <input type="radio" name="status" id="status_opt2" 
            <? if ($msclass->status == 'off') :?>checked="checked"<? endif; ?> value="off" />
        <span>Off</span>
    </label>

    <fieldset>
        <legend>Filters</legend>
        <div class="accordion">
            <label>Expression</label>
            <input type="text" name="expression" value="<?=$msclass->expression?>" />

            <label>Max. Scale</label>
            <input type="text" name="maxscaledenom" value="<?=$msclass->maxscaledenom?>" />

            <label>Min. Scale</label>
            <input type="text" name="minscaledenom" value="<?=$msclass->minscaledenom?>" />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Appearance</legend>
        <div class="accordion">
            
            <label>Text</label>
            <input type="text" name="text" value="<?=$msclass->text?>" />
        
            <label>Symbol&nbsp;
                <a class="linkexplorer" title="Explorador" href="<?=base_url().$dataexplorerctrlpath?>?return=msclasssymbol"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="explorador" title="Explorador" /></a>
            </label>
            <input id="msclasssymbol" type="text" name="symbol" value="<?=$msclass->symbol?>" />

            <label>Size</label>
            <input type="text" name="size" value="<?=$msclass->size?>" />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Colors</legend>
        <div class="accordion">
            
            <label>Color</label>
            <span id="color_palete"></span>
            <input type="text" name="color" value="<?=$msclass->color?>" />

            <label>Background color</label>
            <span id="bgcolor_palete"></span>
            <input type="text" name="bgcolor" value="<?=$msclass->bgcolor?>" />

            <label>Outline color</label>
            <span id="outlinecolor_palete"></span>
            <input type="text" name="outlinecolor" value="<?=$msclass->outlinecolor?>" />
        </div>
    </fieldset>
    <button type="submit">Save</button>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        initInputColorArray(['color', 'bgcolor', 'outlinecolor']);
        $('div.accordion').hide();
        $('form legend').click(function() {
            $(this).parent().find('div.accordion').slideToggle("slow");
	});
        $("a.linkexplorer").fancybox({
            'height': 600,
            'autoDimensions': false,
            'width': 800
        });
    });
</script>