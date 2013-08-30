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

class Stats_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('database/database_model');
    }
    
    public function adminStats() {
        // load library
        $this->load->library('jpgraph');
        
        $graphs = array(
            'visit' => array('image_url' => '', 'title' => 'Visits', 'color' => '#'.dechex(rand(0,10000000))),
            'map' => array('image_url' => '', 'title' => 'Maps', 'color' => '#'.dechex(rand(0,10000000))),
            'layer' => array('image_url' => '', 'title' => 'Layers', 'color' => '#'.dechex(rand(0,10000000))),
            'ticket' => array('image_url' => '', 'title' => 'Tickets', 'color' => '#'.dechex(rand(0,10000000))),
            'rating' => array('image_url' => '', 'title' => 'Ratings', 'color' => '#'.dechex(rand(0,10000000)))
        );
        $date = date('Y-m-d');
        foreach ($graphs as $entity => &$attributes) {
            $graph_filename = "stats_month_{$entity}_{$date}.png";
            $image_url = base_url().'web/data/tmp/'.$graph_filename;
            $attributes['image_url'] = $image_url;
            if (!file_exists($this->config->item('public_data_path').'tmp/'.$graph_filename)) {
                $pattern = $this->config->item('public_data_path').'tmp/'."stats_month_{$entity}_*";
                $this->cleanTmps($pattern);
                
                unset($ydata);
                $ydata[$entity]['values'] = array();
                $ydata[$entity]['legend'] = $attributes['title'];
                $ydata[$entity]['color'] = $attributes['color'];
                
                list($xaxis, $yaxis) = $this->monthsCount($entity);
                $ydata[$entity]['values'] = $yaxis;
                $graph = $this->jpgraph->monthchart($xaxis, $ydata, $attributes['title']);
                $graph->Stroke($this->config->item('public_data_path').'tmp/'.$graph_filename);
            }
        }
        return $graphs;
    }
    
    public function monthsCount($entity) {
        
        $xdata = null;
        $ydata = null;
        for ($i=0; $i <= 30; $i++) {
            $xdata[] = 30 - $i;
            $ydata[30 - $i] = 0;
        }
        
        $sql = "
            select count(last_update::date) as updates, (current_date - last_update::date) as past_days 
            from $entity
            where current_date - last_update::date <= 30
            group by past_days";
        $data = $this->database_model->getAll($sql);
        
        foreach($data as $item) {
            if (!isset($ydata[(int)$item['past_days']])) continue;
            $ydata[(int)$item['past_days']] = (int) $item['updates'];
        }
        
        return array($xdata, $ydata);
    }
    
    public function top10Rated($entity) {
        $sql = "
            select * from rating
            where entity = ?
            order by rating.value desc
            limit 10";
        return $this->database_model->getAll($sql, array($entity));
    }
    
    private function cleanTmps($pattern) {
        foreach (glob($pattern) as $filename) unlink ($filename);
    }
}

?>
