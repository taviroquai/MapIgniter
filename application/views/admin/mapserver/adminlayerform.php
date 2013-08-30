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
    <p><?=$mslayer->layer->title?></p>
    <input type="hidden" name="layer_id" value="<?=$mslayer->layer->id?>" />
    
    <? 
    $pglayers = $mslayer->layer->ownPglayer;
    if (empty($mslayer->id) && !empty($pglayers)) : 
        $pglayer = reset($pglayers); ?>
    <fieldset>
        <legend>Template (optional)</legend>
        <label for="pgplacetype">
            <input name="pgplacetype" id="pgplacetype" type="checkbox" value="<?=$pglayer->pgplacetype?>" checked="checked" />
            <span>Create from Postgis layer (<?=$pglayer->pgplacetype?> table)</span>
        </label>
    </fieldset>
    <? elseif (!empty($pglayers)) : ?>
    <? $pglayer = reset($pglayers); ?>
    <fieldset>
        <legend>Created from Postgis layer (<?=$pglayer->pgplacetype?> table)</legend>
        <input name="pgplacetype" id="pgplacetype" type="hidden" value="<?=$pglayer->pgplacetype?>" />
    </fieldset>
    <? endif; ?>
    
    <fieldset>
        <legend>Status</legend>
    <label for="status_opt1">
        <input type="radio" name="status" id="status_opt1"
            <? if ($mslayer->status == 'on') :?>checked="checked"<? endif; ?> value="on" />
        <span>On</span>
    </label>
    <label for="status_opt2">
        <input type="radio" name="status" id="status_opt2" 
            <? if ($mslayer->status == 'off') :?>checked="checked"<? endif; ?> value="off" />
        <span>Off</span>
    </label>
    </fieldset>
    
    <fieldset>
        <legend>Connection Type</legend>
        <div class="accordion">
            
            <? if (!empty($mslayer->id)) : ?>
                <label>Place type (defined when creating layer)</label>
                <p><?=$mslayer->pgplacetype?></p>
                <input type="hidden" name="pgplacetype" value="<?=$mslayer->pgplacetype?>" />
            <? endif; ?>
            
            <label>Connection Type</label>
            <select name="mslayerconntype_id">
            <? foreach ($mslayerconntypes as $item) { ?>
            <option value="<?=$item->id?>" <?=$item->id == $mslayer->mslayerconntype->id ? 'selected="selected"' : ''?>><?=$item->name?></option>
            <? } ?>    
            </select>

            <label>Connection</label>
            <input type="text" name="connection" value="<?=$mslayer->connection?>" />

            <label>Data&nbsp;
                <a class="linkexplorer fancybox.ajax" title="Explorer" href="<?=base_url().$dataexplorerctrlpath?>?return=mslayerdata"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="explorador" title="Explorer" /></a>
            </label>
            <input id="mslayerdata" type="text" name="data" value="<?=$mslayer->data?>" />

            <label>Dump</label>
            <label for="dump_opt1">
                <input type="radio" name="dump" id="dump_opt1"
                    <? if ($mslayer->dump == 'true') :?>checked="checked"<? endif; ?> value="true" />
                <span>Yes</span>
            </label>
            <label for="dump_opt2">
                <input type="radio" name="dump" id="dump_opt2" 
                    <? if ($mslayer->dump == 'false') :?>checked="checked"<? endif; ?> value="false" />
                <span>No</span>
            </label>
        </div>
    </fieldset>
    <fieldset>
        <legend>Dimensions and measures</legend>
        <div class="accordion">
            <label>Units</label>
            <select name="msunits_id">
            <? foreach ($msunits as $item) { ?>
                <option value="<?=$item->id?>" <?=$item->id == $mslayer->msunits->id ? 'selected="selected"' : ''?>><?=$item->name?></option>
            <? } ?>    
            </select>
            
            <label>Projection</label>
            <textarea name="projection" cols="60" rows="13"><?=$mslayer->projection?></textarea>

            <label>Extent</label>
            <input type="text" name="extent" value="<?=$mslayer->extent?>" />
            
            <label>Max. scale</label>
            <input type="text" name="maxscaledenom" value="<?=$mslayer->maxscaledenom?>" />

            <label>Min. scale</label>
            <input type="text" name="minscaledenom" value="<?=$mslayer->minscaledenom?>" />

            <label>Symbol scale</label>
            <input type="text" name="symbolscaledenom" value="<?=$mslayer->symbolscaledenom?>" />
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Appearance</legend>
        <div class="accordion">
            <label>Opacity</label>
            <input type="text" name="opacity" value="<?=$mslayer->opacity?>" />

            <label>Feature type</label>
            <select name="mslayertype_id">
            <? 
            if (empty($mslayer->mslayertype->id)) $mslayertype_id = 5;
            else $mslayertype_id = $mslayer->mslayertype->id;
            foreach ($mslayertypes as $item) { ?>
                <option value="<?=$item->id?>" <?=$item->id == $mslayertype_id ? 'selected="selected"' : ''?>><?=$item->name?></option>
            <? } ?>    
            </select>

            <label>Class for features (classitem)</label>
            <? if (!empty($datatable)) : ?>
            <select name="classitem">
                <option value=""<?='' == $mslayer->classitem ? ' selected="selected"' : ''?>></option>
            <? foreach ($datatable['fields'] as $item) { ?>
                <option value="<?=$item?>"<?=$item == $mslayer->classitem ? ' selected="selected"' : ''?>><?=$item?></option>
            <? } ?>    
            </select>
            <? else : ?>
            <input type="text" name="classitem" value="<?=$mslayer->classitem?>" />
            <? endif; ?>
            
            <label>Label for features (labelitem)</label>
            <? if (!empty($datatable)) : ?>
            <select name="labelitem">
                <option value=""<?='' == $mslayer->labelitem ? ' selected="selected"' : ''?>></option>
            <? foreach ($datatable['fields'] as $item) { ?>
                <option value="<?=$item?>"<?=$item == $mslayer->labelitem ? ' selected="selected"' : ''?>><?=$item?></option>
            <? } ?>    
            </select>
            <? else : ?>
            <input type="text" name="labelitem" value="<?=$mslayer->labelitem?>" />
            <? endif; ?>

            <label>Template&nbsp;
                <a class="linkexplorer fancybox.ajax" title="Explorer" href="<?=base_url().$dataexplorerctrlpath?>?return=msstylesymbol"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="explorador" title="Explorer" /></a>
            </label>
            <input type="text" name="template" value="<?=$mslayer->template?>" />
        </div>
    </fieldset>
    
    <button type="submit">Save</button>
</form>
<script type="text/javascript">
    $(document).ready(function() {
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