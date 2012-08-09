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
?><h3>Feature Classes</h3>
<? $this->load->view('admin/mapserver/adminclassform'); ?>
<?
$items = $mslayer->ownMsclass;
if (empty($items)) : ?>
<p>There are no classes on this layer</p>
<? else : ?>
<? $this->load->view('admin/mapserver/adminclasslist'); ?>
<? endif; ?>