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

var wfseditfeature = function (mapblock, pglayerid, layerindex, geomtype) {

    // set public variables
    this.mapblock = mapblock;
    this.pglayerid = pglayerid;
    this.geomtype = geomtype;
    this.layer = mapblock.map.layers[layerindex];
    this.vectorLayer = new OpenLayers.Layer.Vector("Selection");
    
    this.mapblock.map.addLayers([this.vectorLayer]);

    this.mapblock.controls.wfscontrol = new OpenLayers.Control.GetFeature({
        // WFS 1.1.0 ISSUE
        // https://github.com/mapserver/mapserver/issues/3998
        protocol: OpenLayers.Protocol.WFS.fromWMSLayer(this.layer, {version: "1.0.0", geometryName: "the_geom"}),
        maxFeatures: 1,
        clickTolerance: 10,
        toggleKey: "ctrlKey"
    });

    var me = this;
    
    this.mapblock.controls.wfscontrol.events.register("featureselected", this, function(e) {
        this.vectorLayer.addFeatures([e.feature]);
        me.message('The place was found');
        jQuery.get(base_url+ctrlpath+'/edit/'+pglayerid+'/'+e.feature.attributes.gid, 
            function(response) {
                //var centroid = e.feature.geometry.getCentroid();
                //me.mapblock.map.panTo(new OpenLayers.LonLat(centroid.x - 0.5*centroid.x, centroid.y-0.5*centroid.y));
                me.editPlacePopup(e.feature, response);
            }
        );
    });
    
    mapblock.controls.wfscontrol.events.register("featureunselected", this, function(e) {
        me.resetMap();
    });
    
    mapblock.map.addControl(mapblock.controls.wfscontrol);
    mapblock.controls.wfscontrol.activate();
    
    mapblock.controls.modify = new OpenLayers.Control.ModifyFeature(this.vectorLayer);
    this.vectorLayer.events.register("featuremodified", this, function(e) {
        var autosave = jQuery('#autosave').attr('checked');
        if (autosave) me.saveGeometry(e.feature);
    });
    mapblock.map.addControl(mapblock.controls.modify);
    mapblock.controls.modify.activate();
    
    switch(geomtype) {
        case 'POINT':mapblock.controls.create = new OpenLayers.Control.DrawFeature(this.vectorLayer, OpenLayers.Handler.Point);
            break;
        case 'LINESTRING':mapblock.controls.create = new OpenLayers.Control.DrawFeature(this.vectorLayer, OpenLayers.Handler.Path);
            break;
        case 'POLYGON':mapblock.controls.create = new OpenLayers.Control.DrawFeature(this.vectorLayer, OpenLayers.Handler.Polygon);
            break;
    }
    mapblock.controls.create.featureAdded = function(feature) {
        me.handleCreate(feature);
    };
    mapblock.map.addControl(mapblock.controls.create);
    
    mapblock.map.addControl(new OpenLayers.Control.MousePosition());
    mapblock.map.addControl(new OpenLayers.Control.LayerSwitcher());
}

wfseditfeature.prototype.handleCreate = function (feature) {
    var me = this;
    jQuery.get(base_url+ctrlpath+'/edit/'+this.pglayerid+'/new', 
        function(response) {
            me.editPlacePopup(feature, response, true);
            jQuery('#create').attr('checked', false);
            me.toggleCreate();
        }
    );
}

wfseditfeature.prototype.toggleCreate = function () {
    var me = this;
    if (jQuery('#create').attr('checked') == 'checked') {
        jQuery('#editbutton').addClass('checked');
        jQuery('#editbutton span').html('Drawing new ...');
        this.mapblock.controls.wfscontrol.deactivate();
        this.resetMap();
        this.mapblock.controls.create.activate();
        me.message('Edition mode. Click on the map to start drawing.', 10000);
    }
    else {
        jQuery('#editbutton').removeClass('checked');
        jQuery('#editbutton span').html('Draw new place');
        this.mapblock.controls.create.deactivate();
        this.mapblock.controls.wfscontrol.activate();
        me.message('Selection mode.', 10000);
    }
}

wfseditfeature.prototype.loadFeature = function(id, srid) {

    // set private variables
    var postgeomformat = new OpenLayers.Format.WKT();
    var me = this;
    
    me.resetMap();
    
    jQuery.getJSON(base_url+ctrlpath+'/ajaxloadplace/'+this.pglayerid+'/'+id+'/'+srid,
        null, function(response) {
            var feature = postgeomformat.read(response.record.wkt);
            feature.attributes = response.record;
            me.vectorLayer.addFeatures([feature]);
            jQuery.get(base_url+ctrlpath+'/edit/'+me.pglayerid+'/'+id, 
                function(response) {
                    //var centroid = feature.geometry.getCentroid();
                    //me.mapblock.map.panTo(new OpenLayers.LonLat(centroid.x - 0.5*centroid.x, centroid.y-0.5*centroid.y));
                    me.editPlacePopup(feature, response);
                }
            );
        }
    );
}

wfseditfeature.prototype.editPlacePopup = function(feature, form, create) {
    
    var centroid = feature.geometry.getCentroid();
    var str = form;
    var me = this;
    var popup = new OpenLayers.Popup("chicken",
               new OpenLayers.LonLat(centroid.x, centroid.y),
               new OpenLayers.Size(450,450),
               str,
               true);
    popup.minSize = new OpenLayers.Size(450,450);
    popup.panMapIfOutOfView = true;
    this.mapblock.map.addPopup(popup);
    popup.updateSize();
    jQuery(popup.contentDiv).css('overflow', 'auto');
    jQuery('#chicken').css('border', '4px solid #e0e0e0');
    this.mapblock.controls.modify.selectFeature(feature);
    
    jQuery('#pgplaceform').each(function() {
        jQuery(this).submit(function(e) {
            e.preventDefault();
            jQuery(this).ajaxSubmit(function(response) {
                jQuery(popup.contentDiv).html(response);
                while( me.mapblock.map.popups.length ) {
                    me.mapblock.map.removePopup(me.mapblock.map.popups[0]);
                }
                var autosave = jQuery('#autosave').attr('checked');
                if (create && response.record.gid) {
                    feature.attributes.gid = response.record.gid;
                    me.saveGeometry(feature, true);
                }
                else {
                    if (autosave) me.saveGeometry(feature);
                    jQuery('#content').load(base_url+ctrlpath+'/listitems/'+me.pglayerid);
                }

            });
            return false;
        });
    });

    // Attach open file explorer event
    jQuery("#pgplaceform a.linkexplorer").click(function(e){
        e.preventDefault();
        var me = this;
        jQuery.fancybox.open(
            [{href: me.href, type: 'ajax'}], {
            'height': 600,
            'autoSize': false,
            'width': 800
        });
    });
}

wfseditfeature.prototype.saveGeometry = function(feature, refresh) {
    var me = this;
    var postgeomformat = new OpenLayers.Format.WKT();
    if (feature.attributes.gid === undefined) {
        alert('Error: can only save existing geometries');
        return;
    }
    jQuery.ajax({
        type: 'POST',
        url: base_url+ctrlpath+'/savegeom/'+me.pglayerid+'/'+feature.attributes.gid,
        data: {
            format: 'wkt',
            proj: me.mapblock.map.projection,
            wkt: postgeomformat.write(feature)
        }, 
        success: function(data) {
            if (data.msgs.errors.length > 0) {
                me.message(data.msgs.errors.join("\n"), 20000);
            }
            else {
                me.message(data.msgs.info.join("\n"));
                me.layer.mergeNewParams({'random':Math.random()});
                me.layer.redraw();
                if (refresh) {
                    jQuery('#content').load(base_url+ctrlpath+'/listitems/'+me.pglayerid);
                }
            }
        }
    });

    me.resetMap();
    this.mapblock.controls.modify.deactivate();
    this.mapblock.controls.modify.activate();
}

wfseditfeature.prototype.resetMap = function() {
    this.vectorLayer.removeAllFeatures();
    while( this.mapblock.map.popups.length ) {
        this.mapblock.map.removePopup(this.mapblock.map.popups[0]);
    }
}

wfseditfeature.prototype.message = function(message, delay) {
    if (!delay) delay = 5000;
    jQuery('#mapmsgs').html('<p>'+message+'</p>');
    jQuery('#mapmsgs').show();
    jQuery('#mapmsgs').animate({opacity:0},200,"linear",function(){
        jQuery(this).animate({opacity:1},200, function() {
            jQuery(this).fadeOut(delay);
        });
    });
}