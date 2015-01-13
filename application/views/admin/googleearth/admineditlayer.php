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
<h2>Configure layer on Google Earth</h2>
<?php if (empty($gelayer)) : ?>
<p>The layer does not exists!</p>
<?php else : ?>
<?php if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<?php $this->load->view('admin/googleearth/adminlayerform'); ?>
<?php endif; ?>