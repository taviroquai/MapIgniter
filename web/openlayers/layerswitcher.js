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

var layerswitcher = function (divEl) {
    this.divEl = divEl;
};

layerswitcher.prototype.config = function (mapblock) {
    
    // set public variables
    this.mapblock = mapblock;
    
    // set private variables
    var me = this;
    var layer;

    jQuery('#'+this.divEl).load(base_url+'openlayers/layerswitcher/'+mapblock.config.map.id+'/'+this.divEl, null, function() {
        
        jQuery('div[id*=layer] input:checkbox').each(function() {
            layer = me.findLayerByAlias(jQuery(this).attr('data-layeralias'));
            if (layer) {
                if (layer.getVisibility()) jQuery(this).attr('checked', true);
                jQuery(this).click(function() {
                    me.toggleByAlias(jQuery(this).attr('data-layeralias'));
                });
            }
        });
        
        jQuery('#'+me.divEl+' .accordion').hide();
        jQuery('#'+me.divEl+' .milayercategorylabel').click(function() {
            jQuery(this).parent().find('ul.accordion').slideToggle("slow");
        });
    });
}

layerswitcher.prototype.toggle = function(layer) {
    if (layer.getVisibility()) {
        layer.setVisibility(false);
    }
    else {
        layer.setVisibility(true);
    }
}

layerswitcher.prototype.toggleByAlias = function(alias) {
    var layer = this.findLayerByAlias(alias);
    if (layer) this.toggle(layer);
}

layerswitcher.prototype.findLayerByAlias = function(alias) {
    var layerconfig, layers;
    for(var i=0; i < this.mapblock.config.layers.length; i++) {
        layerconfig = this.mapblock.config.layers[i];
        if (layerconfig.alias == alias) {
            layers = this.mapblock.map.getLayersByName(layerconfig.name);
            if (layers.length > 0) return layers[0];
        }
    }
    return false;
}