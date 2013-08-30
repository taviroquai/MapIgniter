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

/* ------------------------------------------------------------------------ */

var featuresearch = function (divEl, resultsEl) {
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

featuresearch.prototype.config = function (mapblock) {
    
    // set public variables
    this.mapblock = mapblock;
}

featuresearch.prototype.loadFeature = function (pglayerid, id) {
    
    // set private variables
    var postgeomformat = new OpenLayers.Format.WKT();
    var me = this;
    
    jQuery.getJSON(base_url+'postgis/getfeaturejson/'+pglayerid+'/'+id,
        null, function(response) {
            var feature = postgeomformat.read(response.record.wkt);
            var centroid = feature.geometry.getCentroid();
            me.mapblock.map.panTo(new OpenLayers.LonLat(centroid.x, centroid.y));
            me.mapblock.map.zoomTo(12);
        }
    );
}
