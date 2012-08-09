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
<h2>Ticket - Follow up</h2>
<? if (empty($ticket)) : ?>
<p>Ticket not found!</p>
<? else : ?>
<? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<? $this->load->view('admin/crm/adminticketform', 
        array('ctrlpath' => $ctrlpath, 'ticket' => $ticket)); ?>
<? endif; ?>

<h3>Tracking</h3>
<ul>
    <? if (!empty($ticketlog)) : ?>
    <? $i = 0; ?>
    <? foreach ($ticketlog as $log) { ?>
    <li>
        <span><strong><?=$log->last_update?></strong> <?=$log->subject?></span>
        <table class="ticketlog">
            <tr>
                <th>Created by</th>
                <th>Reference</th>
                <th>Delegated to</th>
                <th>Status</th>
            </tr>
            <tr>
                <td><?=$log->owner == 'guest' ? $log->email : $log->owner?></td>
                <td><?=$log->externalref?></td>
                <td><?=$log->account->username?></td>
                <td><?=$log->status?></td>
            </tr>
            <? if ($i == 0) : ?>
            <tr>
                <td colspan="4"><?=$log->message?></td>
            </tr>
            <? endif; ?>
            <tr>
                <td colspan="4"><?=$log->comments?></td>
            </tr>
        </table>
    </li>
    <? $i++; ?>
    <? } ?>
    <? endif; ?>
    <li>
        <span><strong><?=$ticket->last_update?></strong> <?=$ticket->subject?></span>
        <table class="ticketlog">
            <tr>
                <th>Created by</th>
                <th>Reference</th>
                <th>Delegated to</th>
                <th>Status</th>
            </tr>
            <tr>
                <td><?=$ticket->owner == 'guest' ? $ticket->email : $ticket->owner?></td>
                <td><?=$ticket->externalref?></td>
                <td><?=$ticket->account->username?></td>
                <td><?=$ticket->status?></td>
            </tr>
            <tr>
                <td colspan="4"><?=$ticket->message?></td>
            </tr>
            <tr>
                <td colspan="4"><?=$ticket->comments?></td>
            </tr>
        </table>
    </li>
</ul>

