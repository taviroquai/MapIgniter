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
?><h1>System Requirements</h1>
<?php if (!empty($msgs)) $this->load->view('messages', array('msgs' => $msgs)); ?>

<?php if (empty($msgs['errors'])) : ?>
    <?php if ($installdb) : ?>
        <p>Click <a href="<?=base_url()?>">here</a> to go to the homepage.</p>
    <?php else: ?>
    <form action="<?=base_url()?>admin/install" method="post">
        <p>NOTE: You can now install the database. This operation will destroy current application database and cannot be undone.</p>
        <input type="hidden" name="installdb" value="1" />
        <button type="submit">Install</button>
    </form>
    <?php endif; ?>
<?php endif; ?>
