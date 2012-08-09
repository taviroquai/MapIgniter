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
?><ul class="milayerswitcher">
    <? if (!empty($root['categories'])) : ?>
        <? foreach ($root['categories'] as $item) { ?>
        <li class="micategory">
            <span class="milayercategorylabel"><?=$item['category']->title?></span>
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
    $('div.milayerswitcher .accordion').hide();
    $('div.milayerswitcher .milayercategorylabel').click(function() {
        $(this).parent().find('ul.accordion').slideToggle("slow");
    });
});
</script>