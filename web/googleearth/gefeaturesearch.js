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

var gefeaturesearch = function (divEl, resultsEl) {
    this.divEl = divEl;
    this.resultsEl = resultsEl;
    
    var me = this;
    jQuery('#'+this.divEl+' form').submit(function(e) {
        e.preventDefault();
        jQuery(this).ajaxSubmit(function(response) {
            if (jQuery(me.resultsEl+' .content-padding').length)
                jQuery(me.resultsEl+' .content-padding').html(response);
            else jQuery(me.resultsEl).html(response);
        });
        return false;
    })
};

gefeaturesearch.prototype.config = function (mapblock) {
    
    // set public variables
    this.mapblock = mapblock;
}

gefeaturesearch.prototype.lookAt = function (lat, lon, range) {
    
    if (range === undefined) range = 5000;
    
    // Create a new LookAt.
    var lookAt = this.mapblock.createLookAt('');

    // Set the position values.
    lookAt.setLatitude(lat);
    lookAt.setLongitude(lon);
    lookAt.setRange(range); //default is 0.0

    // Update the view in Google Earth.
    this.mapblock.getView().setAbstractView(lookAt);
}
