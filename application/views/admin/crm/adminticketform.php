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
?><form method="post" action="<?=base_url().$ctrlpath.$action?>">

    <?php if ($owner == 'guest') : ?>
    <label>Email</label>
    <input type="text" name="email" value="<?=$ticket->email?>" />
    <?php else : ?>
    <label>User account</label>
    <p><?=$owner?></p>
    <?php endif; ?>
    <input type="hidden" name="owner" value="<?=$owner?>" />

    <?php if (empty($ticket->id)) : ?>
    <label>Subject</label>
    <input type="text" name="subject" value="<?=$ticket->subject?>" />

    <label>Message</label>
    <textarea name="message"><?=$ticket->message?></textarea>

    <label>Reference</label>
    <input type="text" name="externalref" value="<?=$ticket->externalref?>" />
    <?php else : ?>

    <label>Subject</label>
    <input type="hidden" name="subject" value="<?=$ticket->subject?>" />
    <p><?=$ticket->subject?></p>

    <label>Message</label>
    <input type="hidden" name="message" value="<?=$ticket->message?>" />
    <p><?=$ticket->message?></p>

    <label>Reference</label>
    <input type="hidden" name="externalref" value="<?=$ticket->externalref?>" />
    <p><?=$ticket->externalref?></p>
    
        <?php if (!empty($ticket->layer_id)) : ?>
        <label>Layer</label>
        <p><a href="<?=base_url()?>user/managelayer/edit/<?=$ticket->layer->id?>"><?=$ticket->layer->title?></a></p>
        <label>Place identification</label>
        <p><?=$ticket->featureid?></p>
        <?php endif; ?>
        <?php if (!empty($ticket->pgplacetype)) : ?>
        <label>Place</label>
        <p><a href="<?=base_url()?>user/managefullscreenpgplace/listitems/<?=$ticket->pgplacetype?>">go to place</a></p>
        <?php endif; ?>
    <?php endif; ?>

    <label>Delegated to</label>
    <select name="assigned">
        <?php foreach ($accounts as $account) { ?>
        <option value="<?=$account->id?>" <?=$ticket->account->id == $account->id ? 'selected="selected"' : ''?>><?=$account->username?></option>
        <?php } ?>
    </select>

    <label>Status</label>
    <select name="status">
        <?php foreach ($statusopts as $key => $label) { ?>
        <option value="<?=$key?>" <?=$ticket->status == $key ? 'selected="selected"' : ''?>><?=$label?></option>
        <?php } ?>
    </select>

    <label>Comments</label>
    <textarea name="comments"><?=$ticket->comments?></textarea>

    <button type="submit">Save</button>
</form>