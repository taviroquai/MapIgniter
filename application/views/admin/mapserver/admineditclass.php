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
?>
<h2>Configure features class</h2>
<? if (empty($msclass)) : ?>
<p>The class does not exists!</p>
<? else : ?>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<? $this->load->view('admin/mapserver/adminclassform'); ?>
<?    
endif;
?>
<? if (!$msclass->id) return; ?>

<h4>Label</h4>
<?
$labels = $msclass->sharedMslabel;
if (empty($labels)) : ?>
<? if (empty($mslabels)) : ?>
<p>There are no labels 
Click <a href="<?=base_url().$mslabelctrlpath?>/edit/new?msclass=<?=$msclass->id?>">here</a> to create a label.
</p>
<? else : ?>
    <form method="post" action="<?=base_url().$ctrlpath?>/addlabel/<?=$msclass->id?>">
        <select name="mslabel_id">
            <? foreach ($mslabels as $item) {?>
            <option value="<?=$item->id?>"><?=substr($item->description, 0, 40)?></option>
            <? } ?>
        </select>
        <button type="submit">Add</button>
    </form>
<? endif;
else :
$mslabel = reset($labels); ?>
<p>
    <span><?=$mslabel->description?></span>
    <a href="<?=base_url().$mslabelctrlpath?>/edit/<?=$mslabel->id?>">Configure</a>
    <a href="<?=base_url().$ctrlpath?>dellabel/<?=$msclass->id?>">Remove</a>
</p>
<? endif; ?>

<h4>Style</h4>
<? if (empty($msstyles)) : ?>
<p>There are no styles
Click <a href="<?=base_url().$msstylectrlpath?>/edit/<?=$msclass->id?>">here</a> to create a style.
</p>
<? else : ?>
    <form method="post" action="<?=base_url().$ctrlpath?>/addstyle/<?=$msclass->id?>">
        <select name="msstyle_id">
            <? foreach ($msstyles as $item) {?>
            <option value="<?=$item->id?>"><?=substr($item->description, 0, 40)?></option>
            <? } ?>
        </select>
        <button type="submit">Add</button>
    </form>
    <? 
    $items = $msclass->sharedMsstyle;
    if (!empty($items)) : ?>
    <h4>Styles on this classe</h4>
    <form method="post" action="<?=base_url().$ctrlpath?>/delstyle/<?=$msclass->id?>">
        <ul>
            <? foreach ($items as $item) {
            ?>
            <li>
                <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                <a href="<?=base_url().$msstylectrlpath?>/edit/<?=$item->id?>">Configure</a>
                <span><?=$item->description?></span>
            </li>
            <? } ?>
        </ul>
        <button type="submit">Remove selected</button>
    </form>
    <? endif; 
endif;
?>