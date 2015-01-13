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
<h2>Layers</h2>
<a class="novo" href="#">New</a>
<div class="accordion">
    <?php $this->load->view('admin/layer/adminlayerform'); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('div.accordion').hide();
        $('a.novo').click(function() {
            $(this).parent().find('div.accordion').slideToggle("slow");
	});
    });
</script>
<?php if (empty($items)) : ?>
<p>There are no layers yet.</p>
<?php else : ?>
<h3>List of layers</h3>
<form method="post" action="<?=base_url().$ctrlpath?>/delete">
    <ul>
        <?php foreach ($items as $item) { ?>
        <li>
            <input type="checkbox" name="selected[]" value="<?=$item->id?>" />
            <a href="<?=base_url().$ctrlpath?>/edit/<?=$item->id?>">Configure</a>
            <span><?=$item->title?></span>
        </li>
        <?php } ?>
    </ul>
    <button type="submit">Remove selected</button>
</form>
<?php endif; ?>