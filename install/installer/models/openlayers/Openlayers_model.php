<?php


/**
 * MapIgniter
 *
 * An open source GeoCMS application
 *
 * @package		MapIgniter
 * @author		Marco Afonso
 * @copyright	Copyright (c) 2012-2013, Marco Afonso
 * @license		dual license, one of two: Apache v2 or GPL
 * @link		http://mapigniter.com/
 * @since		Version 1.1
 * @filesource
 */

// ------------------------------------------------------------------------

class Openlayers_model extends CI_Model {
    
    protected $tempdir;
    protected $tempurl;
    
    public function __construct() {
        parent::__construct();
        
        $this->tempdir = $this->config->item('public_data_path').'tmp';
        $this->tempurl = base_url().'web/data/tmp';
        
        $this->load->model('database_model');
        $this->load->model('map_model');
    }
    
    public function createLayerType($type = 'New Type', $classname = 'OpenLayers') {
        $ollayertype = $this->database_model->create('ollayertype');
        $ollayertype->type = $type;
        $ollayertype->classname = $classname;
        return $ollayertype;
    }
    
    public function loadLayerType($id) {
        return $this->database_model->load('ollayertype', $id);
    }
    
    public function loadLayerTypeAll() {
        return $this->database_model->find('ollayertype', ' true ');
    }
    
    public function deleteLayerType($ids) {
        return $this->database_model->delete('ollayertype', $ids);
    }
    
    public function createLayer($layer, $ollayertype, $url = '', $options = '[]', $vendorparams = '[]') {
        $ollayer = $this->database_model->create('ollayer');
        $ollayer->layer = $layer;
        $ollayer->ollayertype = $ollayertype;
        $ollayer->url = $url;
        $ollayer->options = $options;
        $ollayer->vendorparams = $vendorparams;
        $ollayer->informationurl = '';
        $ollayer->last_update = date('Y-m-d H:i:s');
        
        return $ollayer;
    }
    
    public function loadLayer($id) {
        return $this->database_model->load('ollayer', $id);
    }
    
    public function loadLayerAll() {
        return $this->database_model->find('ollayer', ' true ');
    }
    
    public function findWMSLayer($tablename) {
        $mslayers = $this->database_model->find('mslayer', " pgplacetype = ? ", array($tablename));
        $list = array();
        if (!empty($mslayers)) {
            foreach ($mslayers as $mslayer) {
                $ollayer = $this->database_model->findOne('ollayer', " layer_id = ? ", array($mslayer->layer->id));
                if (!empty($ollayer)) {
                    $list[] = $ollayer;
                }
            }
        }
        return $list;
    }
    
    public function findMapByLayers($ollayers) {
        $list = array();
        foreach ($ollayers as $ollayer) {
            $olmaps = $this->database_model->related($ollayer, 'olmap');
            if (!empty($olmaps)) $list = array_merge($list, $olmaps);
        }
        return $list;
    }
    
    public function findMapLayerIndexByPlaceType($olmap, $tablename) {
        
        if (empty($olmap)) return null;
        
        $ollayers = $olmap->sharedOllayer;
        $index = 0;
        foreach ($ollayers as $ollayer) {
            if ($ollayer->ollayertype->id == 4) {
                $mslayer = $this->database_model->findOne('mslayer', ' layer_id = ? ', array($ollayer->layer->id));
                if ($mslayer && $mslayer->pgplacetype == $tablename) {
                    return $index;
                }
            }
            $index++;
        }
    }
    
    public function deleteLayer($ids) {
        return $this->database_model->delete('ollayer', $ids);
    }
    
    public function createMap($map) {
        $olmap = $this->database_model->create('olmap');
        $olmap->map = $map;
        $olmap->name = $map->title;
        $olmap->projection = "EPSG:900913";
        $olmap->autoresolution = 0;
        $olmap->numzoomlevels = 19;
        $olmap->last_update = date('Y-m-d H:i:s');
        
        /*
         *  OpenLayers 2.12 changes
         *  https://github.com/openlayers/openlayers/blob/master/notes/2.12.md
         *  There are now default values for maxExtent, maxResolution and units
         */
        //$olmap->maxextent = "[\n-128 * 156543.03390625,\n-128 * 156543.03390625,\n128 * 156543.03390625,\n128 * 156543.03390625\n]";
        //$olmap->maxextent = "[\n-20037508.34,\n-20037508.34,\n20037508.34,\n20037508.34\n]";
        //$olmap->maxresolution = 156543.03390625;
        //$olmap->units = 'm';
        $olmap->maxextent = '';
        $olmap->restrictedextent = '';
        $olmap->maxresolution = null;
        $olmap->units = '';
        // END
        
        return $olmap;
    }
    
    public function exportMap($olmap, $layers = true) {
        $map = array();
        $map['id'] = $olmap->id;
        $map['units'] = $olmap->units;
        $map['projection'] = $olmap->projection;
        
        /*
         *  OpenLayers 2.12 changes
         *  https://github.com/openlayers/openlayers/blob/master/notes/2.12.md
         */
        if (!empty($olmap->maxextent)) $map['maxExtent'] = json_decode($olmap->maxextent, true);
        if (!empty($olmap->restrictedextent)) $map['restrictedExtent'] = json_decode($olmap->restrictedextent, true);
        if (!empty($olmap->maxresolution)) $map['maxResolution'] = (float) $olmap->maxresolution;
        // END
        
        $map['autoResolution'] = (bool) $olmap->autoresolution;
        $map['numZoomLevels'] = (int) $olmap->numzoomlevels;
        if (!$layers) return array('map' => $map);
        
        $layers = array();
        $ollayers = $olmap->sharedOllayer;
        if (!empty($ollayers)) {
            foreach ($ollayers as &$ollayer) {
                $layers[] = $this->openlayers_model->exportLayer($ollayer);
            }
        }
        return array('map' => $map, 'layers' => $layers);
    }
    
    public function exportLayer($ollayer) {
        $export = array();
        $export['id'] = $ollayer->id;
        $export['name'] = $ollayer->layer->title;
        $export['alias'] = $ollayer->layer->alias;
        $export['url'] = $ollayer->url;
        $export['type'] = $ollayer->ollayertype->export();
        $export['options'] = json_decode($ollayer->options, true);
        $export['vendorparams'] = json_decode($ollayer->vendorparams, true);
        return $export;
    }
    
    public function loadMap($id) {
        return $this->database_model->load('olmap', $id);
    }
    
    public function loadMapAll() {
        return $this->database_model->find('olmap', ' true ');
    }
    
    public function deleteMap($ids) {
        return $this->database_model->delete('olmap', $ids);
    }
    
    public function addMapLayer(&$olmap, &$ollayer) {
        $olmap->sharedOllayer[]= $ollayer;
        $this->save($olmap);
    }
    
    public function delMapLayer(&$olmap, $ids) {
        $items = $olmap->sharedOllayer;
        foreach ($items as $item) {
            if (in_array($item->id, $ids)) unset($olmap->sharedOllayer[$item->id]);
        }
        $this->save($olmap);
    }
    
    public function save(&$bean)
    {
        return $this->database_model->save($bean);
    }
    
    public function printmap($request, $server)
    {
        // Check for requirements
        if (!extension_loaded('gd')) throw new Exception('GD extension not found!');
        
        // fetch the request params, and generate the name of the tempfile and its URL
        $width    = @$request['width'];  if (!$width) $width = 1024;
        $height   = @$request['height']; if (!$height) $height = 768;
        $tiles    = json_decode(@$request['tiles']);
        //$tiles    = json_decode(stripslashes(@$_REQUEST['tiles'])); // use this if you use magic_quotes_gpc
        $random   = md5(microtime().mt_rand());
        $file     = sprintf("%s/%s.jpg", $this->tempdir, $random );
        $url      = sprintf("%s/%s.jpg", $this->tempurl, $random );

        // lay down an image canvas
        // Notice: in MapServer if you have set a background color
        // (eg. IMAGECOLOR 60 100 145) that color is your transparent value
        // $transparent = imagecolorallocatealpha($image,60,100,145,127);
        
        $image = @imagecreatetruecolor($width,$height);
        imagefill($image,0,0, imagecolorallocate($image,255,255,255) ); // fill with white

        // loop through the tiles, blitting each one onto the canvas
        foreach ($tiles as $tile) {
            // try to convert relative URLs into full URLs
            // this could probably use some improvement
            $tile->url = urldecode($tile->url);
            if (substr($tile->url,0,4)!=='http') {
                $tile->url = preg_replace('/^\.\//',dirname($server['REQUEST_URI']).'/',$tile->url);
                $tile->url = preg_replace('/^\.\.\//',dirname($server['REQUEST_URI']).'/../',$tile->url);
                $tile->url = sprintf("%s://%s:%d/%s", isset($server['HTTPS'])?'https':'http', $server['SERVER_ADDR'], $server['SERVER_PORT'], $tile->url);
            }
            $tile->url = str_replace(' ','+',$tile->url);

            // fetch the tile into a temp file, and analyze its type; bail if it's invalid
            $tempfile =  sprintf("%s/%s.img", $this->tempdir, md5(microtime().mt_rand()) );
            file_put_contents($tempfile,file_get_contents($tile->url));
            list($tilewidth,$tileheight,$tileformat) = @getimagesize($tempfile);
            if (!$tileformat) continue;

            switch ($tileformat) {
                case 1:
                    $tileimage = imagecreatefromgif($tempfile);
                    break;
                case 2:
                    $tileimage = imagecreatefromjpeg($tempfile);
                    break;
                case 3:
                    $tileimage = imagecreatefrompng($tempfile);
                    $dstimage = imagecreatetruecolor($width,$height);
                    $black = imagecolorallocate($dstimage, 0, 0, 0);
                    imagecolortransparent($dstimage, $black);
                    imagealphablending($dstimage, false);
                    imagecopymerge($dstimage,$tileimage,0,0,0,0, $tilewidth,$tileheight,100);
                    $tileimage = $dstimage;
                   break;
               default: continue;
            }
            
            $this->imagecopymerge_alpha($image, $tileimage, $tile->x, $tile->y, 0, 0, $tilewidth, $tileheight, $tile->opacity);
            
        }
        
        // Clean up all tiles
        foreach(glob($this->tempdir.'/*.img') as $v) unlink($v);

        // save to disk and tell the client where they can pick it up
        imagejpeg($image, $file, 100);
        return $url;

    }
    
    private function imagecopymerge_alpha(&$dst_im, &$src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity) {
        $r2 = imagecopymerge($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
    }

}

?>
