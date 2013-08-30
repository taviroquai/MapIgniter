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

$num = rand(10000, 99999);
?>
<div itemscope="itemscope" itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating">
    <div id="rate_<?=$num?>" class="rateme"
         data-rate="<?=empty($rate['bean']) ? '0' : $rate['bean']->value?>" 
         data-ratedone="<?=$rate['done']?>" 
         data-ratecode="<?=$rate['code']?>" 
         style="background-position: <?=empty($rate['bean']) ? -80 : -80 + $rate['bean']->value * 16?>px 0px">
        <img src="<?=base_url()?>web/images/icons/png/16x16/rate.png" alt="*" /><img src="<?=base_url()?>web/images/icons/png/16x16/rate.png" alt="*" /><img src="<?=base_url()?>web/images/icons/png/16x16/rate.png" alt="*" /><img src="<?=base_url()?>web/images/icons/png/16x16/rate.png" alt="*" /><img src="<?=base_url()?>web/images/icons/png/16x16/rate.png" alt="*" />
    </div>
    <div class="ratemsg">Saved!</div>
    <span class="hidden" itemprop="ratingCount"><?=empty($rate['bean']) ? '0' : $rate['bean']->votes?></span>
    <span class="hidden" itemprop="ratingValue"><?=empty($rate['bean']) ? '0' : $rate['bean']->value?></span>
</div>
<br style="clear: left;" />
<script>
    var featurerating = new rating('rate_<?=$num?>');
</script>