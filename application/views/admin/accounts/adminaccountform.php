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
?><form method="post" action="<?=base_url().$ctrlpath?>/save/<?=empty($account->id) ? 'new' : $account->id?>">
    <label>Username <span title="Text field">?</span></label>
    <?php if (empty($account->id)) :?>
    <input type="text" name="username" value="<?=$account->username?>" />
    <?php else: ?>
    <p><?=$account->username?></p>
    <input type="hidden" name="username" value="<?=$account->username?>" />
    <?php endif; ?>
    <label>Email <span title="E-mail; Valid characters: TODO">?</span></label>
    <input type="text" name="email" value="<?=$account->email?>" />
    <label>Password <span title="Valid characteres: TODO">?</span></label>
    <input type="password" name="password" value="" />
    <?php if (empty($account->id)) :?>
    <label>Create user group</label>
    <label for="creategroup_opt1">
        <input type="radio" name="creategroup" id="creategroup_opt1" checked="checked" value="1" />
        <span>Yes</span>
    </label>
    <label for="creategroup_opt2">
        <input type="radio" name="creategroup" id="creategroup_opt2" value="0" />
        <span>No</span>
    </label>
    <?php endif; ?>
    <button type="submit">Save</button>
</form>