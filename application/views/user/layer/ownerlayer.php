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
<h2>My Layers</h2>
<a class="novo" href="#">New</a>
<div class="accordion">
    <? $this->load->view('user/layer/ownerlayerform'); ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('div.accordion').hide();
        $('a.novo').click(function() {
            $(this).parent().find('div.accordion').slideToggle("slow");
	});
    });
</script>
<? if (empty($items)) : ?>
<p>There are no layers</p>
<? else : ?>
<h3>List of layers</h3>
<form method="post" action="<?=base_url().$ctrlpath?>/delete">
    <ul>
        <? foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url().$ctrlpath?>/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->title?></span>
            <? $this->load->view('rate', array('rate' => $rating[$item->id])); ?>
        </li>
        <? } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<? endif; ?>