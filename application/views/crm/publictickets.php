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
<? if (!empty($read)) : ?>
    <? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
    <form action="<?=base_url().$ctrlpath?>/read?" method="get">
        <label><?=$this->lang->line('ticket.email.label')?></label>
        <input type="text" name="email" value="<?=$form['email']?>" />
        <label><?=$this->lang->line('ticket.ref.label')?></label>
        <input type="text" name="ref" value="<?=$form['ref']?>" />
        <button type="submit"><?=$this->lang->line('ticket.read.submit')?></button>
    </form>
    <? if (!empty($ticket)) : ?>
    <ul>
        <? if (!empty($ticketlog)) : ?>
        <? $i = 0; ?>
        <? foreach ($ticketlog as $log) { ?>
        <li>
            <span><strong><?=$log->last_update?></strong> <?=$log->subject?></span>
            <table class="ticketlog">
                <tr>
                    <th><?=$this->lang->line('ticket.created.label')?></th>
                    <th><?=$this->lang->line('ticket.ref.label')?></th>
                    <th><?=$this->lang->line('ticket.delegated.label')?></th>
                    <th><?=$this->lang->line('ticket.status.label')?></th>
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
                    <th><?=$this->lang->line('ticket.created.label')?></th>
                    <th><?=$this->lang->line('ticket.ref.label')?></th>
                    <th><?=$this->lang->line('ticket.delegated.label')?></th>
                    <th><?=$this->lang->line('ticket.status.label')?></th>
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
    <? endif; ?>
<? else :?>
    <? if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
    <? if (empty($success)) : ?>
    <? $this->load->view('crm/publicticketform', 
        array('ctrlpath' => $ctrlpath, 'ticket' => $newticket)); ?>
    <? else : ?>
        <? $this->load->view('crm/publiccreateticketsuccess',
        array('ctrlpath' => $ctrlpath, 'ticket' => $ticket)); ?>
    <? endif; ?>
<? endif; ?>
<p><?=sprintf($this->lang->line('ticket.page.backlink'), base_url())?></p>