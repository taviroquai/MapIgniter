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

var urls = [
    "http://a.tile.openstreetmap.org/${z}/${x}/${y}.png",
    "http://b.tile.openstreetmap.org/${z}/${x}/${y}.png",
    "http://c.tile.openstreetmap.org/${z}/${x}/${y}.png"
];

var map = new OpenLayers.Map({
    div: "map",
    layers: [
        new OpenLayers.Layer.XYZ("OSM (with buffer)", urls, {
            transitionEffect: "resize", buffer: 2, sphericalMercator: true
        }),
        new OpenLayers.Layer.XYZ("OSM (without buffer)", urls, {
            transitionEffect: "resize", buffer: 0, sphericalMercator: true
        })
    ],
    controls: [
        new OpenLayers.Control.Navigation({
            dragPanOptions: {
                enableKinetic: true
            }
        }),
        new OpenLayers.Control.PanZoom(),
        new OpenLayers.Control.Attribution()
    ],
    center: [0, 0],
    zoom: 3
});

map.addControl(new OpenLayers.Control.LayerSwitcher());

var proj1 = new OpenLayers.Projection("EPSG:4326");
var center = new OpenLayers.LonLat(-7.8,37);
center.transform(proj1, map.getProjectionObject());
map.setCenter(center,8);