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

/**
 * Creating WebSig Namespace
 */
var WebSig = {VERSION: 10070};

/**
 * Define Constants
 */
OpenLayers.ProxyHost = base_url+"proxy?url=";
OpenLayers.ImgPath = base_url+"web/js/ol/img/";

/**
 * Define classes
 */
WebSig.Mapblock = function (name, config) {
    
    this.blockname = name;
    this.config = config;
    this.divEl = 'map_'+this.blockname;
    this.map = null;
    this.controls = {};
    this.print_wait_win = null;
    this.config.map.allOverlays = true;
    this.config.map.displayProjection = 'EPSG:4326';
}

/**
 * WebSig.Mapblock.init
 */
WebSig.Mapblock.prototype.init = function () {

    this.map = new OpenLayers.Map(this.divEl, this.config.map);
    
    var layer = null;
    for (var l=0; l < this.config.layers.length; l++) {
        var lcfg = this.config.layers[l];
        switch(lcfg.type.classname) {
            case 'OpenLayers.Layer.OSM':
                layer = new OpenLayers.Layer.OSM(lcfg.name);
                break;
            case 'OpenLayers.Layer.Google':
                layer = new OpenLayers.Layer.Google(lcfg.name, lcfg.vendorparams, lcfg.options);
                break;
            case 'OpenLayers.Layer.Bing':
                lcfg.vendorparams.name = lcfg.name;
                layer = new OpenLayers.Layer.Bing(lcfg.vendorparams, lcfg.options);
                break;
            default:
                if (lcfg.type.id == 4) lcfg.url = base_url + 'mapserver/map/' + lcfg.url;
                if (lcfg.autoResolution) lcfg.maxResolution = "auto";
                layer = new OpenLayers.Layer.WMS(lcfg.name, lcfg.url, lcfg.vendorparams, lcfg.options);
        }
        this.map.addLayers([layer]);
    }
    //this.map.addControl(new OpenLayers.Control.LayerSwitcher());
}

/**
 * WebSig.Mapblock.render
 */
WebSig.Mapblock.prototype.render = function(lon, lat, zoom, proj) {
    if (proj == undefined) proj = 'EPSG:4326';
    var center = new OpenLayers.LonLat(lon, lat);
    var proj1 = new OpenLayers.Projection(proj);
    center.transform(proj1, this.map.getProjectionObject());
    this.map.setCenter(center, zoom);
}

/**
 * WebSig.Mapblock.renderExtent
 */
WebSig.Mapblock.prototype.renderExtent = function() {
    this.map.zoomToMaxExtent();
}

/**
 * Run User function
 */
WebSig.Mapblock.prototype.run = function (func) {
    func(this);
}

/**
 * WebSig.Mapblock.prototype.print
 */
WebSig.Mapblock.prototype.print = function () {
    
    //TODO: remove to outside
    this.printurl = base_url+'openlayers/printmap';

    // go through all layers, and collect a list of objects
    // each object is a tile's URL and the tile's pixel location relative to the viewport
    var offsetX = parseInt(this.map.layerContainerDiv.style.left);
    var offsetY = parseInt(this.map.layerContainerDiv.style.top);
    var size  = this.map.getSize();
    var tiles = [];
    for (layername in this.map.layers) {
        // if the layer isn't visible at this range, or is turned off, skip it
        var layer = this.map.layers[layername];
        if (!layer.getVisibility()) continue;
        if (!layer.calculateInRange()) continue;
        // iterate through their grid's tiles, collecting each tile's extent and pixel location at this moment
        for (tilerow in layer.grid) {
            for (tilei in layer.grid[tilerow]) {
                var tile     = layer.grid[tilerow][tilei];
                var url      = layer.getURL(tile.bounds);
                var position = tile.position;
                var tilexpos = position.x + offsetX;
                var tileypos = position.y + offsetY;
                var opacity  = layer.opacity ? parseInt(100*layer.opacity) : 100;
                tiles[tiles.length] = {url:url, x:tilexpos, y:tileypos, opacity:opacity};
            }
        }
    }

    // hand off the list to our server-side script, which will do the heavy lifting
    var tiles_json = JSON.stringify(tiles);
    var printparams = 'width='+size.w + '&height='+size.h + '&tiles='+escape(tiles_json) ;
    jQuery('#printResponse').html('<p>Por favor espere...</p>');

    OpenLayers.Request.POST(
      {url: this.printurl,
        data:OpenLayers.Util.getParameterString({width:size.w,height:size.h,tiles:tiles_json}),
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        callback: function(request) {
           //window.open(request.responseText);
           jQuery('#printResponse').html(request.responseText);
        }
      }
    );
}