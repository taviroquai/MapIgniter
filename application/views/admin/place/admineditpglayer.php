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
<h2>Postgis Layer</h2>
<ul class="tabs">
    <li><a class="active" href="#editpglayer">Configure</a></li>
    <? if (!empty($table)) : ?>
    <li><a href="#editattributes">Attributes</a></li>
    <? endif; ?>
</ul>
<ul class="tabs-content">
    <li class="active" id="editpglayer">
        <h3>Configure</h3>
        <? if (empty($pglayer)) : ?>
        <p>The Postgis layer does not exists!</p>
        <? else : ?>
        <? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
        <? $this->load->view('admin/place/adminpglayerform'); ?>
        <? endif; ?>
    </li>
    <? if (!empty($table)) : ?>
    <li id="editattributes">
        <h3>Attributes</h3>
        <? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
        <? $this->load->view('admin/place/adminpglayerattributesform'); ?>
    </li>
    <? endif; ?>
</ul>