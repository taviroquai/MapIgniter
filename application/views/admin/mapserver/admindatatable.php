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

/**
 * @param Array $datatable Holds 'error', 'fields', 'srs' and 'extent' attributes
 */
?>
<h3>Information</h3>
<?php if (!empty($datatable['error'])) : ?>
<div class="msgs"><p class="error"><?=$datatable['error']?></p></div>
<?php else: ?>
<p><strong>SRS:</strong> <?=$datatable['srs']?></p>
<p><strong>EXTENT:</strong> <?=$datatable['extent']?></p>
<h4>Attributes</h4>
<ul>
<?php foreach ($datatable['fields'] as $field) { ?>
    <li><?=$field?></li>
<?php } ?>
</ul>
<?php endif; ?>