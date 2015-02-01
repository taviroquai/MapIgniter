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
?><form method="post" action="<?=base_url()?>admin/adminlayouts/edit/<?=empty($layout->id) ? 'new' : $layout->id?>">
    <label>System name</label>
    <input type="text" name="name" value="<?=$layout->name?>" />
    <label>Page Title</label>
    <input type="text" name="pagetitle" value="<?=$layout->pagetitle?>" />
    <label>Page Description</label>
    <input type="text" name="pagedescription" value="<?=$layout->pagedescription?>" />
    <label>Page Keywords</label>
    <input type="text" name="pagekeywords" value="<?=$layout->pagekeywords?>" />
    <label>Page Author</label>
    <input type="text" name="pageauthor" value="<?=$layout->pageauthor?>" />
    <label>Page Logo
        <a class="linkexplorer fancybox.ajax" title="Explorer" href="<?=base_url($dataexplorerctrlpath)?>?return=pagelogo">
            <img src="<?=base_url()?>web/images/icons/png/16x16/search.png" alt="explorador" title="Explorer" />
        </a>
    </label>
    <input type="text" id="pagelogo" name="pagelogo" value="<?=$layout->pagelogo?>" />
    <label>PHP View</label>
    <input type="text" name="view" value="<?=$layout->view?>" />
    <label>Content</label>
    <textarea name="content" class="wysiwyg"><?=empty($layout->content) ? '' : $layout->content?></textarea>
    <button type="submit">Save</button>
</form>