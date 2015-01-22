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

class Googleearth extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('googleearth/googleearth_model');
        $this->load->model('rating/rating_model');
    }
    
    public function index()
    {
        
    }
    
    public function getConfig($gemapid) {
        
        $this->load->model('googleearth/modgemap_lblock');
        $main_block = $this->modgemap_lblock;
        
        $gemap = $this->googleearth_model->loadMap($gemapid);
        $config = $this->googleearth_model->exportMap($gemap);
        
        header('Content-type: application/json');
        echo json_encode($config);
    }
    
    public function kml($gemapid) {
        
        $gemap = $this->googleearth_model->loadMap($gemapid);
        $gelayers = $gemap->sharedGelayer;
        $results = array();
        $lookat = null;
        if (!empty($gelayers)) {
            $this->load->model('database/postgis_model');
            foreach ($gelayers as &$gelayer) {
                $pglayers = $gelayer->layer->ownPglayer;
                if (!empty($pglayers)) {
                    $pglayer = reset($pglayers);
                    $table = $this->postgis_model->loadTable($pglayer->pgplacetype);
                    $tresults = $this->postgis_model->getTableKML($table);
                    if (!empty($tresults) && empty($lookat)) {
                        $lookat = reset($tresults);
                    }
                    $results = array_merge($results, $tresults);
                }
            }
        }
        
        // Creates the Document.
        $dom = new DOMDocument('1.0', 'UTF-8');

        // Creates the root KML element and appends it to the root document.
        $node = $dom->createElementNS('http://www.opengis.net/kml/2.2', 'kml');
        $parNode = $dom->appendChild($node);

        // Creates a KML Document element and append it to the KML element.
        $dnode = $dom->createElement('Document');
        $docNode = $parNode->appendChild($dnode);
        
        // Create Camera node
        $lookat_node = $dom->createElement('LookAt');
        $docNode->appendChild($lookat_node);
        $lookx_node = $dom->createElement('longitude', $lookat['x']);
        $lookat_node->appendChild($lookx_node);
        $looky_node = $dom->createElement('latitude', $lookat['y']);
        $lookat_node->appendChild($looky_node);
        $lookalt_node = $dom->createElement('altitude', 1000000);
        $lookat_node->appendChild($lookalt_node);
        $lookrange_node = $dom->createElement('range', 1000000);
        $lookat_node->appendChild($lookrange_node);
        
        // Create styles here

        if (!empty($results)) {
            foreach ($results as $item) {
                // Creates a Placemark and append it to the Document.

                $node = $dom->createElement('Placemark');
                $placeNode = $docNode->appendChild($node);

                // Creates an id attribute and assign it the value of id column.
                $placeNode->setAttribute('id', $gelayer->layer->alias . $item['gid']);

                // Create name, and description elements and assigns them the values of the name and address columns from the results.
                $nameNode = $dom->createElement('name', htmlentities($item['title']));
                $placeNode->appendChild($nameNode);
                $descNode = $dom->createElement('description');
                $cdata = $dom->createCDATASection($item['description']);
                $descNode->appendChild($cdata);
                $placeNode->appendChild($descNode);
                //$styleUrl = $dom->createElement('styleUrl', '#' . $row['type'] . 'Style');
                //$placeNode->appendChild($styleUrl);

                // Adds Geometry
                $xmlobj = simplexml_load_string($item['kml']);
                if ($xmlobj === false) continue;
                $tnode = dom_import_simplexml($xmlobj);
                if (!$tnode) continue;
                $geom_node = $dom->importNode($tnode, true);
                $placeNode->appendChild($geom_node);
            }
        }

        $kmlOutput = $dom->saveXML();
        header("Pragma: public", true);
        header("Expires: 0"); // set expiration time
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=".'gemap.kml');
        header('Content-type: application/vnd.google-earth.kml+xml');
        echo $kmlOutput;
    }
    
    public function search($gemapid) {
        
        $results = array();
        $terms = $this->input->get('q');
        $jsinstance = 'block_'.$this->input->get('_instance');
        
        $gemap = $this->googleearth_model->loadMap($gemapid);
        $gelayers = $gemap->sharedGelayer;
        
        if (!empty($gelayers)) {
            $this->load->model('database/postgis_model');
            foreach ($gelayers as &$gelayer) {
                $pglayers = $gelayer->layer->ownPglayer;
                if (!empty($pglayers)) {
                    $pglayer = reset($pglayers);
                    $table = $this->postgis_model->loadTable($pglayer->pgplacetype);
                    $records = $this->postgis_model->findRecordsByTerms($table, $terms);
                    if (!empty($records)) $results[$gelayer->layer->alias] = 
                        array('pglayer' => $pglayer, 'records' => $records);
                }
            }
        }
        
        // Prepare data
        $data = array('results' => $results, '_instance' => $jsinstance);
        $content = $this->load->view('googleearth/gesearchresults', $data, TRUE);
        $this->render($content);
    }
    
}