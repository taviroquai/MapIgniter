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
?><script>
    var block_<?=$_instance?> = new wfsgetfeaturepopup('<?=$_instance?>', '<?=$item->alias?>', '<?=empty($config['popupfunction']) ? '' : $config['popupfunction']?>', '<?=empty($config['htmlurl']) ? '' : $config['htmlurl']?>');
    new WebSig.after('block_<?=$config['mapblock']?>', function() {
        block_<?=$_instance?>.config(block_<?=$config['mapblock']?>);
    });
</script>
