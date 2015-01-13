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
<h2>Ticket - Follow up</h2>
<?php if (empty($ticket)) : ?>
<p>Ticket not found!</p>
<?php else : ?>
<?php if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<?php $this->load->view('admin/crm/adminticketform', 
        array('ctrlpath' => $ctrlpath, 'ticket' => $ticket)); ?>
<?php endif; ?>

<h3>Tracking</h3>
<ul>
    <?php if (!empty($ticketlog)) : ?>
    <?php $i = 0; ?>
    <?php foreach ($ticketlog as $log) { ?>
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
            <?php if ($i == 0) : ?>
            <tr>
                <td colspan="4"><?=$log->message?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td colspan="4"><?=$log->comments?></td>
            </tr>
        </table>
    </li>
    <?php $i++; ?>
    <?php } ?>
    <?php endif; ?>
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

