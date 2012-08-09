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
?><h1>System Requirements</h1>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>

<? if (empty($msgs['errors'])) : ?>
<form action="<?=base_url()?>admin/install" method="post">
    <p>NOTE: You can now reinstall the database. This operation cannot be undone.</p>
    <input type="hidden" name="installdb" value="1" />
    <button type="submit">Reinstall</button>
</form>
<? endif; ?>
<p>Click <a href="<?=base_url()?>">here</a> to go to the homepage.</p>