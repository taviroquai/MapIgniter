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

/* ------------------------------------------------------------------------ */

var rating = function(elem) {
    this.elem = elem;

    jQuery('#'+this.elem+'.rateme img').hover(function() {
        var elem = jQuery(this).parent();
        if (jQuery(elem).attr('data-ratedone') == '0') {
            var numstars = jQuery(elem).children().length;
            var pos = (jQuery(this).width()* -(numstars-1)) 
                + jQuery(this).position().left - jQuery(elem).position().left;
            jQuery(elem).css({backgroundPosition: pos.toString()+'px 0px'});
        }
    }, function() {
        var elem = jQuery(this).parent();
        if (jQuery(elem).attr('data-ratedone') == '0') {
            var numstars = jQuery(elem).children().length;
            var rate = (numstars - jQuery(elem).attr('data-rate')) 
                * jQuery(this).width() * -1;
            jQuery(elem).css({backgroundPosition: rate+'px 0px'});
        }
    });
    jQuery('#'+this.elem+'.rateme img').click(function() {
        var elem = jQuery(this).parent();
        if (jQuery(elem).attr('data-ratedone') == '0') {
            var numstars = jQuery(elem).children().length;
            var pos = (jQuery(this).width()* -(numstars-1)) + 
                jQuery(this).position().left - jQuery(elem).position().left;
            var code = jQuery(elem).attr('data-ratecode');
            var vote = (jQuery(elem).width() + pos) / jQuery(this).width();
            jQuery.post(
                base_url+'rating/vote/'+vote, 
                {code: code}, 
                function(response) {
                    jQuery(elem).css({backgroundPosition: pos.toString()+'px 0px'});
                    jQuery(elem).attr('data-rate', vote);
                    jQuery(elem).attr('data-ratedone', '1');
                    jQuery(elem).next().fadeIn('slow', function() {
                        jQuery(this).fadeOut();
                    });
                },
                "json"
            );
        }
    });
}