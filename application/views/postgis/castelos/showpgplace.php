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
?><div style="float:right;">
    <small>
        <a href="<?=base_url()?>tickets/create/<?=$layeralias?>/<?=$record['gid']?>">Report a problem</a>
    </small>
</div>
<h3><?=$record['title']?></h3>
<img src="<?=$record['image']?>" alt="Imagem do castelo" style="float: left; margin: 10px;"/>
<div><?=$record['description']?></div>
