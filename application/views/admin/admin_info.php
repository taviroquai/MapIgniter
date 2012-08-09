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
?><h2>Administration</h2>
<h3>Activity</h3>
<? foreach ($graphs as $entity => &$attributes) { ?>
<p>
    <img src="<?=$attributes['image_url']?>" />
</p>
<? } ?>