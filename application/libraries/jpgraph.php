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

// DIRTY HACK: Fix imageantialias problem in some systems
// http://ubuntuincident.wordpress.com/2011/01/28/drawing-graphs-in-php-with-jpgraph/
// Thank you Guillame
if(!function_exists('imageantialias'))
{
    function imageantialias($image, $enabled)
    {
        return false;
    }
}

class Jpgraph {
    
    function __construct() {
        
        // Get JpGraph
        include(APPPATH.'/third_party/vendor/jpgraph/src/jpgraph.php');
        include(APPPATH.'/third_party/vendor/jpgraph/src/jpgraph_line.php');
    }
    
    function monthchart($xdata, $ydata, $title='Line Chart')
    {
        // Create the graph. These two calls are always required
        $graph = new Graph(600,250,"auto",60);
        $graph->img->SetAntiAliasing(false);
        $graph->SetScale("textlin");
        $graph->xaxis->SetTickLabels($xdata);
        $graph->xgrid->SetColor('#E3E3E3');
        $graph->legend->SetFrameWeight(1);
        
        // Setup title
        $graph->title->Set($title);
        
        foreach ($ydata as $item) {
            // Create the linear plot
            if (count($item['values']) != count($xdata)) continue;
            $lineplot=new LinePlot($item['values'], $xdata);
            $lineplot->SetColor($item['color']);
            if (count($ydata) == 1) $lineplot->SetFillColor($item['color']);
            
            // Add the plot to the graph
            $graph->Add($lineplot);
            $lineplot->SetLegend($item['legend']);
        }
        return $graph;
    }
}

?>
