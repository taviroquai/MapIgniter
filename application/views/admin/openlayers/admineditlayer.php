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
<h2>Configure layer on OpenLayers</h2>
<? if (empty($ollayer)) : ?>
<p>The layer does not exists!</p>
<? else : ?>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<? $this->load->view('admin/openlayers/adminlayerform'); ?>
<? endif; ?>