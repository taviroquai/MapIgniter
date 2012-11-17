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

$total_files = count($info['cache_list']);
$total_hits = 0;
$total_size = 0;
foreach ($info['cache_list'] as $item) {
    $total_hits += $item['num_hits'];
    $total_size += $item['mem_size'];
}

// Convert units
$units = array('B', 'KB', 'MB', 'GB', 'TB'); 
$bytes = max($total_size, 0);
$pow = floor(($total_size ? log($total_size) : 0) / log(1024)); 
$pow = min($pow, count($units) - 1); 
$bytes /= pow(1024, $pow);

?><h2>Administration</h2>
<h3>APC Cache Information</h3>
<table class="cacheinfo">
    <tr>
        <th>Parameter</th><th>Value</th>
    </tr>
    <tr>
        <td>Number of Slots</td><td><?=$info['num_slots']?></td>
    </tr>
    <tr>
        <td>Time to Live</td><td><?=$info['ttl']?></td>
    </tr>
    <tr>
        <td>Number of Hits</td><td><?=$info['num_hits']?></td>
    </tr>
    <tr>
        <td>Number of Misses</td><td><?=$info['num_misses']?></td>
    </tr>
    <tr>
        <td>Start Time</td><td><?=$info['start_time']?></td>
    </tr>
    <tr>
        <td>Number of Files</td><td><?=$total_files?></td>
    </tr>
    <tr>
        <td>Number of Hits</td><td><?=$total_hits?></td>
    </tr>
    <tr>
        <td>Total Memory Size</td><td><?=round($bytes, 2) . ' ' . $units[$pow]?> (<a href="<?=base_url()?>admin/admincache/clear">Clear Cache</a>)</td>
    </tr>
</table>