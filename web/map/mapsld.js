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

OpenLayers.ProxyHost = base_url+"proxy?url=";
    
var map, controls, layer1, highlightlayer;

jQuery(document).ready(function($) {
    load();
});

function load() {
    map = new OpenLayers.Map('map', {

        maxExtent: new OpenLayers.Bounds(
                -128 * 156543.03390625,
                -128 * 156543.03390625,
                128 * 156543.03390625,
                128 * 156543.03390625
            ),
            maxResolution: 156543.03390625,
            numZoomLevels: 19,
            units: "m",
            projection: "EPSG:900913"

    });

    var osm = new OpenLayers.Layer.OSM('OSM');
    var gsat = new OpenLayers.Layer.Google(
        "Google Satellite",
        {type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22}, {isBaseLayer: true}
    );
    var aerial = new OpenLayers.Layer.Bing({
        name: "Aerial",
        key: "AqTGBsziZHIJYYxgivLBf0hVdrAk9mWO5cQcb8Yux8sW5M8c8opEC2lZqKR1ZZXf",
        type: "Aerial"
    }, {isBaseLayer: true});

    layer1 = new OpenLayers.Layer.WMS("Postgis + SLD",
        base_url+"mapserver?map=postgis.map", 
        {
            'layers': 'layer1', 
            transparent: true, 
            format: 'image/png',
            projection: 'EPSG:20790'
        },
        {isBaseLayer: false}
    );

    highlightLayer = new OpenLayers.Layer.Vector("Highlighted Features", {
        displayInLayerSwitcher: false, 
        isBaseLayer: false 
        }
    );

    controls = {
        click: new OpenLayers.Control.WMSGetFeatureInfo({
            url: base_url+'mapserver?map=shape.map&', 
            title: 'Identify features by clicking',
            layers: [layer1],
            queryVisible: true
        })
    };

    map.addLayers([osm, gsat, aerial, layer1, highlightLayer]);

    for (var i in controls) { 
        controls[i].events.register("getfeatureinfo", this, showInfo);
        map.addControl(controls[i]); 
    }

    //map.addControl(new OpenLayers.Control.LayerSwitcher());

    controls.click.activate();
    //map.zoomToMaxExtent();

    var center = new OpenLayers.LonLat(-7.845, 39.58);
    var proj1 = new OpenLayers.Projection('EPSG:4326');
    center.transform(proj1, map.getProjectionObject());
    map.setCenter(center, 7);
}

function showInfo(evt) {
    if (evt.features && evt.features.length) {
         highlightLayer.destroyFeatures();
         highlightLayer.addFeatures(evt.features);
         highlightLayer.redraw();
    } else {
        if ($('responseText'))
            $('responseText').innerHTML = evt.text;
    }
}

// this assumes that the Map object is a JavaScript variable named "map"
var print_wait_win = null;
function PrintMap() {
    //-- post a wait message
    alert("One moment please");

    // go through all layers, and collect a list of objects
    // each object is a tile's URL and the tile's pixel location relative to the viewport
    var offsetX = parseInt(map.layerContainerDiv.style.left);
    var offsetY = parseInt(map.layerContainerDiv.style.top);
    var size  = map.getSize();
    var tiles = [];
    for (layername in map.layers) {
        // if the layer isn't visible at this range, or is turned off, skip it
        var layer = map.layers[layername];
        if (!layer.getVisibility()) continue;
        if (!layer.calculateInRange()) continue;
        // iterate through their grid's tiles, collecting each tile's extent and pixel location at this moment
        for (tilerow in layer.grid) {
            for (tilei in layer.grid[tilerow]) {
                var tile     = layer.grid[tilerow][tilei]
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
    $('responseText').innerHTML = '<p>Por favor espere...</p>';

    OpenLayers.Request.POST(
      { url: base_url+'testes/mapwms/printmap',
        data:OpenLayers.Util.getParameterString({width:size.w,height:size.h,tiles:tiles_json}),
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        callback: function(request) {
           //window.open(request.responseText);
           $('responseText').innerHTML = request.responseText;
        }
      }
    );
}