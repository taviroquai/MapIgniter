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
?><div class="msgs">
    <?php foreach ($msgs['errors'] as $error) { ?>
    <p class="error"><?=$error?></p>
    <?php } ?>
    <?php foreach ($msgs['info'] as $msg) { ?>
    <p class="info"><?=$msg?></p>
    <?php } ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('div.msgs').animate({opacity:0},200,"linear",function(){
            $(this).animate({opacity:1},200);
        });
    });
</script>