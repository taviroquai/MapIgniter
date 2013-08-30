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

/*
 * Record
 * Attibutos:
 *  gid
 *  last_update
 *  owner
 */
?><div style="float:right;">
    <small>
        <a href="<?=base_url()?>tickets/create/<?=$layeralias?>/<?=$record['gid']?>">Report a problem</a>
        <a href="<?=base_url()?>postgis/getfeature/<?=$record['gid']?>/<?=$layeralias?>">Full page</a>
    </small>
</div>
<? foreach ($table->attributes as $field => $type) { 
    if (in_array($field, $sysfields)) continue;
    ?>
    <p><strong><?=$field?>:</strong>&nbsp;<?=$record[$field]?></p>
<? } ?>
<? $this->load->view('rate', array('rate' => $rating[$layeralias.'.'.$record['gid']])); ?>