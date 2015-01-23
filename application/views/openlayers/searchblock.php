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
?><div class="lblock">
    <h2><?=$this->lang->line('search.page.title')?></h2>
    <div id="<?=$_instance?>">
        <form action="<?=base_url()?>openlayers/search/<?=$item->id?>" method="GET">
            <input name="_instance" type="hidden" value="<?=$_instance?>" />
            <input name="q" type="text" />
            <button type="submit" class="btn btn-default"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="search" /></button>
        </form>
        <div style="clear: both"></div>
    </div>
    <script>
        var block_<?=$_instance?> = new featuresearch('<?=$_instance?>', '#slot-content', '<?=empty($config) ? '' : json_encode($config)?>');
        new WebSig.after('block_<?=$item->name?>', function() {
            block_<?=$_instance?>.setMapBlock(block_<?=$item->name?>);
        });
    </script>
</div>
