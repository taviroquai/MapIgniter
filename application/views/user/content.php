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
?><h3>Dashboard</h3>

<h4>Top 10 Layers</h4>
<?php if (!empty($layers)) { ?>
    <ul>
    <?php  foreach ($layers as $item) { ?>
    <li id="layer_<?=$item->alias?>">
        <span class="milayer"><?=$item->title?></span>
        <?php $this->load->view('rate', array('rate' => $layers_rating[$item->id])); ?>
    </li>
    <?php } ?>
    </ul>
<?php } else { ?>
<p>There are no layers rated yet.</p>
<?php } ?>

<h4>Top 10 Places</h4>
<?php if (!empty($locals)) { ?>
<ul>
    <?php foreach ($locals as $layer) { ?>
    <li>Locais em <?=$layer['pglayer']->layer->title?><br />
        <ul>
            <?php  foreach ($layer['records'] as $item) { ?>
            <li id="pgrecord_<?=$item['gid']?>">
                <span class="mipgrecord"><?=empty($item['title']) ? $item['gid'] : $item['title']?></span>
                <?php $this->load->view('rate', array('rate' => $locals_rating[$layer['pglayer']->layer->alias.'.'.$item['gid']])); ?>
            </li>
            <?php } ?>
        </ul>
    <?php } ?>
    </ul>
<?php } else { ?>
<p>There are no rated places yet.</p>
<?php } ?>