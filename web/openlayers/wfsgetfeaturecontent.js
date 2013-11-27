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

var wfsgetfeaturecontent = function(instance, config) {
    this.name = instance;
    this.config = JSON.parse(config);
};

wfsgetfeaturecontent.prototype.setMapBlock = function (mapblock) {

    // Set public vars
    this.mapblock = mapblock;
    
    // Get layer
    var index = null;
    for (var i=0; i<this.mapblock.map.layers.length; i++) {
        for (var j=0; j<this.mapblock.config.layers.length; j++) {
            if (this.mapblock.map.layers[i].name == this.mapblock.config.layers[j].name) {
                if (this.config.layer == this.mapblock.config.layers[i].alias) {
                    index = i;
                    break;break;
                }
            }
        }
    }
    if (index === null) {
        alert('Layer ' + this.config.layer + ' for ' + this.name + ' was not found!');
        return;
    }
    this.layer = mapblock.map.layers[index];
    

    // Add specific get feature controls
    mapblock.controls.wfscontrol = new OpenLayers.Control.GetFeature({
        // HACK: needs "PropertyName" parameter for version 1.1.0
        protocol: OpenLayers.Protocol.WFS.fromWMSLayer(this.layer, {version: "1.0.0", geometryName: "the_geom"}),
        // HACK: to do not set a limit of features. There is different behaviour using box:true and click
        //hover: true,
        maxFeatures: null,
        clickTolerance: 20,
        toggleKey: "ctrlKey"
    });

    var me = this;
    mapblock.controls.wfscontrol.events.register("featureselected", this, function(e) {
        me.show(e);
    });
    mapblock.controls.wfscontrol.events.register("featureunselected", this, function(e) {
        
    });
    mapblock.map.addControl(mapblock.controls.wfscontrol);
    mapblock.map.addControl(new OpenLayers.Control.MousePosition());
    mapblock.controls.wfscontrol.activate();
}

wfsgetfeaturecontent.prototype.show = function (e) {
    
    var feature = e.feature;

    // Prepare html to show
    if (this.config && this.config.htmlurl) {
        
        // First make the call
        jQuery.get(this.config.htmlurl+'/'+feature.attributes.gid+'/'+this.config.layer, null, function(html) {
            var centroid = feature.geometry.getCentroid();
            jQuery('#slot-content').html(html);
        });
    }
    else {

        // Create HTML from feature attributes
        var exclude = ['gid', 'alias', 'last_update', 'owner'];
        var html = '<h2>Feature Information</h2>';
        html += '<table class="featuretable">';
        for(var attr in feature.attributes) {
            if (feature.attributes.hasOwnProperty(attr) && !inArray(attr, exclude)) {
                html += '<tr>';
                html += '<th>'+attr+'</th>';
                html += '<td>'+feature.attributes[attr]+'</td>'
                html += '</tr>';
            }
        }
        html += '</table>';
        html = html + '<div style="float:right;"><small><a href="'+base_url+'tickets/create/'+this.config.layer+'/'+feature.attributes.gid+'">Report a problem</a></small></div>';
        jQuery('#slot-content').html(html);
    }
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
