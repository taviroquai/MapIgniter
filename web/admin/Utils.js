
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

// Earth Tone Color Scheme
// http://www.creativecolorschemes.com/resources/free-color-schemes/earth-tone-color-scheme.shtml
var earth_palete = [ '#f00', '#0f0', '#00f', '#000', '#444', '#888', '#ccc', '#fff', '#493829', '#816c5b', '#A9A18c', '#613318', '#855723', '#b99c6b', '#8f3b1b', '#d57500', '#db6a69', '#404f24', '#668d3c', '#bdd09f', '#4e6172', '#83929f', '#a3adb8'];
        
function rgb2values(rgbString) {
    var parts = rgbString.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    if (parts !== null) {
        delete (parts[0]);
        return jQuery.trim(parts.join(' '));
    }
}
function values2rgb(valuesString) {
    var parts = valuesString.split(' ');
    return 'rgb('+parts.join(', ')+')';
}

function inputElemSetRgb(elem, c) {
    var rgb = rgb2values(c);
    if (rgb !== null) {
        elem.val(rgb2values(c));
        elem.css('background-color',c);
    }
}

function initInputColor(name, allowed_colors) {
    var elem = jQuery('input[name="'+name+'"]');
    if (allowed_colors === undefined) allowed_colors = earth_palete;
    
    inputElemSetRgb(elem, values2rgb(elem.val()));
    jQuery(function($){
        $('#'+name+'_palete').empty().addColorPicker({
            clickCallback: function(c) {
                inputElemSetRgb(elem, c);
            },
            colors: allowed_colors,
            iterationCallback: function(target,elem,color,iterationNumber) {
                elem.html('&nbsp;&nbsp;');
            }
        });
    });
}

function initInputColorArray(array) {
    var i, name, elem;
    var max = array.length;
    for (i = 0; i < max; i++) {
        name = array[i];
        elem = jQuery('input[name="'+name+'"]')[0];
        initInputColor(name);
        jQuery(elem).blur(function() { 
            inputElemSetRgb(jQuery(this), values2rgb(jQuery(this).val())); 
        });
    }
}