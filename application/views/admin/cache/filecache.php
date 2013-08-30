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
?><h2>Administration</h2>
<h3>File Cache Information</h3>
<table class="cacheinfo">
    <tr>
        <th>Parameter</th><th>Value</th>
    </tr>
    <tr>
        <td>Number of Items</td><td><?=$info['total_files']?></td>
    </tr>
    <tr>
        <td>Total Size</td><td><?=$info['total_size']?> (<a href="<?=base_url()?>admin/admincache/clear">Clear Cache</a>)</td>
    </tr>
</table>