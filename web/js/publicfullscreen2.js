jQuery(function($) {
    $('#column-toggle').click(function(e) {
        e.preventDefault();

        var me = $(this);
        if ($('#column').css('left') == '0px') {
            $('#column').stop().animate({ left: '-350px' }, 700, 'swing', function() {
                me.find('img').attr('src', 'web/images/icons/png/32x32/arrow-right.png');
                publicfullscreen2MoveZoomLeft('50px !important');
            });
        }
        else {
            $('#column').stop().animate({ left: '0px' }, 700, 'swing', function() {
                me.find('img').attr('src', 'web/images/icons/png/32x32/arrow-left.png');
                publicfullscreen2MoveZoomLeft('400px !important');
            });
        }
        return false;
    });
});

function publicfullscreen2MoveZoomLeft(css_left) {
    if (css_left === undefined) css_left = '400px !important';
    if (jQuery('#mapcontainer .olControlZoom').length > 0) {
        var css_pos = jQuery('#mapcontainer .olControlZoom').css('position');
        var css_z = jQuery('#mapcontainer .olControlZoom').css('z-index');
        var new_style = 'left: '+css_left+'; position: '+css_pos+'; z-index: '+css_z+';';
        jQuery('#mapcontainer .olControlZoom').attr('style', new_style);
    }
}