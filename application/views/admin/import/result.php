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
<h2>Import Result</h2>
<? $this->load->view('messages'); ?>
<? if ($ok) : ?>
<p>Import was successful!</p>
<? else : ?>
<p>Import has failed.</p>
<? endif; ?>
<p>You can see import logs at <a target="_blank" href="<?=base_url().'web/data/'.$logfile?>"><?=$logfile?></a></p>

