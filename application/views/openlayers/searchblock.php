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
?><div class="lblock">
    <div id="<?=$_instance?>">
        <form action="<?=base_url()?>openlayers/search/<?=$item->id?>" method="GET">
            <input name="_instance" type="hidden" value="<?=$_instance?>" />
            <input name="q" type="text" style="width: 165px; float: left" />
            <button type="submit" style="float: left; padding: 7px 10px 2px 10px; margin-top: 0"><img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="search" /></button>
        </form>
    </div>
    <script>
        var block_<?=$_instance?> = new featuresearch('<?=$_instance?>', '#slot-content');
    </script>
</div>
