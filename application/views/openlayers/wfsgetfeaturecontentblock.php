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
?>
<style>
    table.featuretable {
        width: 100%;
        border: 1px solid gray;
    }
    table.featuretable th {
        padding: 2px 10px;
        border-bottom: 1px solid gray;
        background-color: #e0e0e0;
        text-align: left;
    }
    table.featuretable td {
        padding: 2px 10px;
        border-bottom: 1px solid gray;
    }
</style>
<script>
    var block_<?=$_instance?> = new wfsgetfeaturecontent('<?=$_instance?>', '<?=empty($config) ? '' : json_encode($config)?>');
    new WebSig.after('block_<?=$item->name?>', function() {
        block_<?=$_instance?>.setMapBlock(block_<?=$item->name?>);
    });
</script>
