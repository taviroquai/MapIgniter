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
<h2>Configure Google Earth Layer Type</h2>
<? if (empty($gelayertype)) : ?>
<p>The layer type does not exists!</p>
<? else : ?>
<? $this->load->view('admin/googleearth/adminlayertypeform'); ?>
<? endif; ?>