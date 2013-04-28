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
?><form method="post" action="<?=base_url()?><?=$action?>">
    <fieldset>
        <legend>New layer type</legend>
        <div class="accordion">
            <label>Type</label>
            <input type="text" name="type" value="<?=$gelayertype->type?>" />

            <button type="submit">Salvar</button>
        </div>
    </fieldset>
</form>
