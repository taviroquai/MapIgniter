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
?>
<h2>Edit Place</h2>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<? if (empty($record)) : ?>
<p>The place does not exists!</p>
<? else : ?>
<? $this->load->view('admin/place/adminpgplaceform'); ?>
<? endif; ?>
