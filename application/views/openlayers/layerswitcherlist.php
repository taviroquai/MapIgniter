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
?><ul class="milayerswitcher">
    <? if (!empty($root['categories'])) : ?>
        <? foreach ($root['categories'] as $item) { ?>
        <li class="micategory">
            <span class="milayercategorylabel"><?=$item['category']->title?></span>
            <div class="milayer_description"><?=$item['category']->description?></div>
            <ul class="accordion">
            <? if (!empty($item['layers'])) $this->load->view('openlayers/layerswitcherlayerlist', array('layers' => $item['layers'])); ?>
            </ul>
        </li>
        <? } ?>
    <? endif; ?>
    <? if (!empty($root['layers'])) $this->load->view('openlayers/layerswitcherlayerlist', array('layers' => $root['layers'])); ?>
</ul>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('.milayerswitcher .accordion').hide();
    $('.milayerswitcher .milayercategorylabel').click(function() {
        $(this).parent().find('.accordion').slideToggle("slow");
    });
    $('.milayerswitcher .milayer').on('click', function() {
        $(this).parent().find('.accordion').slideToggle("slow");
    });
});
</script>