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
?><h2><?=$this->lang->line('title')?></h2>
<div id="<?=$_instance?>">Layers</div>
<script>
    var block_<?=$_instance?> = new layerswitcher('<?=$_instance?>');
</script>
