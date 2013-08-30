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
?><script>
    var block_<?=$_instance?> = new wfsgetfeaturepopup('<?=$_instance?>', '<?=empty($config) ? '' : json_encode($config)?>');
    new WebSig.after('block_<?=$item->name?>', function() {
        block_<?=$_instance?>.setMapblock(block_<?=$item->name?>);
    });
</script>
