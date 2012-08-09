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
<h2>Postgis Layers</h2>
<? if (empty($items)) : ?>
<p>There are no layers</p>
<? else : ?>
<ul>
    <? foreach ($items as $item) { ?>
    <li>
        <a href="<?=base_url().$ctrlpath?>/listitems/<?=$item->id?>"><?=$item->layer->title?></a>
    </li>
    <? } ?>
</ul>

<? endif; ?>