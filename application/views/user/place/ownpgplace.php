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
<h2>List of Places</h2>
<? if (empty($items)) : ?>
<p>There are no Postgis layers</p>
<? else : ?>
<ul>
    <? foreach ($items as $item) { ?>
    <li>
        <a href="<?=base_url().$ctrlpath?>/listitems/<?=$item->id?>"><?=$item->layer->title?></a>
        <? $this->load->view('rate', array('rate' => $rating[$item->id])); ?>
    </li>
    <? } ?>
</ul>

<? endif; ?>