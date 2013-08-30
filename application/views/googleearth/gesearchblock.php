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
        <form action="<?=base_url()?>googleearth/search/<?=$item->id?>" method="GET">
            <input name="_instance" type="hidden" value="<?=$_instance?>" />
            <input name="q" type="text" style="width: 280px; float: left" />
            <button type="submit" style="float: left; padding: 4px 10px 4px 10px; margin-bottom: 10px"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="search" /></button>
        </form>
        <div style="clear: both"></div>
    </div>
    <script>
        var block_<?=$_instance?> = new gefeaturesearch('<?=$_instance?>', '#slot-content');
        new WebSig.after('block_<?=$config['mapblock']?>', function() {
            block_<?=$_instance?>.config(block_<?=$config['mapblock']?>);
        });
    </script>
</div>
