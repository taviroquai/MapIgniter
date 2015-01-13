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
<h2>Configure user account</h2>
<?php if (empty($account)) : ?>
<p>User account not found!</p>
<?php else : ?>
<?php if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>
<?php $this->load->view('admin/accounts/adminaccountform', 
        array('ctrlpath' => $ctrlpath, 'account' => $account)); ?>
<h2>User roles</h2>
    <?php if (empty($groups)) : ?>
    <p>There are no user roles available yet.</p>
    <?php else : ?>
    <form action="<?=base_url().$ctrlpath?>/savegroups/<?=$account->id?>" method="post">
        <ul>
        <?php foreach ($groups as $group) { ?>
            <li>
                <input name="groups[]" type="checkbox" value="<?=$group->id?>"
                <?php foreach ($account->sharedGroup as $accgroup) {
                    if ($accgroup == $group) echo ' checked="checked" ';
                }?> />
                <?=$group->name?></li>
        <?php } ?>
        </ul>
        <button type="submit">Save</button>
    </form>
    <?php endif; ?>
<?php endif; ?>