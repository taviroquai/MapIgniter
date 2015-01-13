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
<h2>Postgis Layer</h2>
<ul class="tabs">
    <li><a class="active" href="#editpglayer">Configure</a></li>
    <?php if (!empty($table)) : ?>
    <li><a href="#editattributes">Attributes</a></li>
    <?php endif; ?>
</ul>
<ul class="tabs-content">
    <li class="active" id="editpglayer">
        <h3>Configure</h3>
        <?php if (empty($pglayer)) : ?>
        <p>The Postgis layer does not exists!</p>
        <?php else : ?>
        <?php if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
        <?php $this->load->view('admin/place/adminpglayerform'); ?>
        <?php endif; ?>
    </li>
    <?php if (!empty($table)) : ?>
    <li id="editattributes">
        <h3>Attributes</h3>
        <?php if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
        <?php $this->load->view('admin/place/adminpglayerattributesform'); ?>
    </li>
    <?php endif; ?>
</ul>