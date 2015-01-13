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
<h2>Edit Place</h2>
<?php if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<?php if (empty($record)) : ?>
<p>The place does not exists!</p>
<?php else : ?>
<?php $this->load->view('admin/place/adminpgplaceform'); ?>
<?php endif; ?>
