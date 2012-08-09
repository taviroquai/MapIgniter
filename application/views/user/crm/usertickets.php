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
<h2>My Tickets</h2>
<ul class="tabs">
  <li><a class="active" href="#ticket-list">List</a></li>
  <li><a href="#ticket-create">Create a new ticket</a></li>
</ul>
<ul class="tabs-content">
  <li class="active" id="ticket-list">
    <? if (empty($items)) : ?>
    <p>There are no tickets</p>
    <? else : ?>
        <ul>
            <? foreach ($items as $item) { ?>
            <li>
                <span><?=$item->subject?></span>
                <a href="<?=base_url().$ctrlpath?>/edit/<?=$item->id?>">Follow up</a>
                <? $this->load->view('rate', array('rate' => $rating[$item->id])); ?>
            </li>
            <? } ?>
        </ul>
    <? endif; ?>
  </li>
  <li id="ticket-create">
      <? $this->load->view('admin/crm/adminticketform', 
            array('ctrlpath' => $ctrlpath, 'action', 'ticket' => $ticket)); ?>
  </li>
