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

if (empty($item)) : ?>
<p>Invalid menu!</p>
<?php else : ?>
<ul>
    <?php
    if (!empty($items)) :
        foreach ($items as $item) { 
        $base = empty($item->internal) ? '' : base_url();
        ?>
    <li><a href="<?=$base.$item->href?>"><?=$item->label?></a></li>
    <?php }
    endif; ?>
</ul>
<?php endif;