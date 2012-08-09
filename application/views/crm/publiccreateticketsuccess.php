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
?><p><?=$this->lang->line('ticket.result.createsuccess')?></p>
<p><strong><?=$this->lang->line('ticket.result.emaillabel')?></strong><?=$ticket->email?></p>
<p><strong><?=$this->lang->line('ticket.result.reflabel')?></strong><?=$ticket->externalref?></p>
<p><?=$this->lang->line('ticket.result.copylabel')?></p>
<p><?=$this->lang->line('ticket.result.linklabel')?><br />
    <a href="<?=base_url()?>tickets/read?email=<?=urlencode($ticket->email)?>&amp;ref=<?=$ticket->externalref?>"><?=base_url()?>tickets/read?email=<?=urlencode($ticket->email)?>&amp;ref=<?=$ticket->externalref?></a></p>
<p><?=$this->lang->line('ticket.result.formlabel')?><a href="<?=base_url()?>tickets/read"><?=base_url()?><?=$this->lang->line('ticket.result.formlink')?></a></p>
<p><?=$this->lang->line('ticket.result.thanks')?></p>