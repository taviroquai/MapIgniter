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
?><form method="post" action="<?=base_url().$ctrlpath?>/create">

    <label><?=$this->lang->line('ticket.send.emaillabel')?></label>
    <input type="text" name="email" value="<?=$newticket->email?>" />
    <input type="hidden" name="owner" value="<?=$owner?>" />

    <label><?=$this->lang->line('ticket.send.subjectlabel')?></label>
    <input type="text" name="subject" value="<?=$newticket->subject?>" />

    <label><?=$this->lang->line('ticket.send.messagelabel')?></label>
    <textarea name="message"><?=$newticket->message?></textarea>

    <input type="hidden" name="externalref" value="<?=$newticket->externalref?>" />
    <input type="hidden" name="assigned" value="" />
    <input type="hidden" name="status" value="unconfirmed" />
    <input type="hidden" name="comments" value="" />
    <input type="hidden" name="layeralias" value="<?=$layeralias?>" />
    <input type="hidden" name="featureid" value="<?=$featureid?>" />
    <input type="hidden" name="pgplacetype" value="<?=$pgplacetype?>" />

    <button type="submit"><?=$this->lang->line('ticket.send.submit')?></button>
</form>