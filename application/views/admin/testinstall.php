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
?><h1>Testing install</h1>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<p>Click <a href="<?=base_url()?>admin/testinstall">here</a> to repeat database install</p>