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
?><h3>OpenLayers layer types</h3>
<?php $this->load->view('admin/openlayers/adminlayertypeform'); ?>
<?php if (empty($items)) : ?>
<p>There are no layer types.</p>
<?php else : ?>
<?php $this->load->view('admin/openlayers/adminlayertypelist'); ?>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('div.accordion').hide();
        $('form legend').click(function() {
            $(this).parent().find('div.accordion').slideToggle("slow");
	});
    });
</script>