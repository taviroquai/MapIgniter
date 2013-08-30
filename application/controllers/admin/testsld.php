<?php 

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

// ------------------------------------------------------------------------

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testsld extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {

        // Set response headers
        header("Content-Type: text/xml");

        $base_url = base_url();
        
        echo <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<StyledLayerDescriptor version="1.0.0"
  xmlns="http://www.opengis.net/sld"
  xmlns:ogc="http://www.opengis.net/ogc"
  xmlns:xlink="http://www.w3.org/1999/xlink"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.opengis.net/sld
  http://schemas.opengis.net/sld/1.0.0/StyledLayerDescriptor.xsd">
    <NamedLayer>
        <Name>layer1</Name>
        <UserStyle>
            <Title>Teste</Title>
            <FeatureTypeStyle>
                <Rule>
                    <PointSymbolizer>
                        <Geometry>
                            <PropertyName>the_geom</PropertyName>
                        </Geometry>
                        <Graphic>
                            <ExternalGraphic>
                                <OnlineResource xlink:href="{$base_url}web/js/ol/img/marker.png" />
                                <Format>image/png</Format>
                            </ExternalGraphic>
                            <Size>20</Size>
                        </Graphic>
                    </PointSymbolizer>
                    <TextSymbolizer>
                        <Label>concelho</Label>
                        <Font>
                            <CssParameter name="font-family">Georgia</CssParameter>
                            <CssParameter name="font-size">10</CssParameter>
                        </Font>
                        <LabelPlacement>
                            <PointPlacement>
                                <Displacement>
                                    <DisplacementX>10</DisplacementX>
                                    <DisplacementY>10</DisplacementY>
                                </Displacement>
                            </PointPlacement>
                        </LabelPlacement>
                        <Fill>
                            <CssParameter name="fill">#000000</CssParameter>
                        </Fill>
                    </TextSymbolizer>
                </Rule>
            </FeatureTypeStyle>
        </UserStyle>
    </NamedLayer>
</StyledLayerDescriptor>
EOT;

    }
    
}