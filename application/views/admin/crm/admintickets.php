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
<h2>Tickets</h2>
<ul class="tabs">
  <li><a class="active" href="#ticket-list">List</a></li>
  <li><a href="#ticket-create">New</a></li>
</ul>
<ul class="tabs-content">
  <li class="active" id="ticket-list">
    <? if (empty($items)) : ?>
    <p>There are no tickets</p>
    <? else : ?>
    <form method="post" action="<?=base_url().$ctrlpath?>/delete">
        <ul>
            <? foreach ($items as $item) { ?>
            <li>
                <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
                <a href="<?=base_url().$ctrlpath?>/edit/<?=$item->id?>">Follow up</a>
                <span><?=$item->subject?></span>
            </li>
            <? } ?>
        </ul>
        <button type="submit">Remove selected</button>
    </form>
    <? endif; ?>
  </li>
  <li id="ticket-create">
      <? $this->load->view('admin/crm/adminticketform', 
            array('ctrlpath' => $ctrlpath, 'action', 'ticket' => $ticket)); ?>
  </li>
