<?php


/**
 * MapIgniter
 *
 * An open source GeoCMS application
 *
 * @package		MapIgniter
 * @author		Marco Afonso
 * @copyright	Copyright (c) 2012-2013-2013, Marco Afonso
 * @license		dual license, one of two: Apache v2 or GPL
 * @link		http://mapigniter.com/
 * @since		Version 1.1
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Description of Database_model
 * 
 * @author mafonso
 */
class Database_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->library('rb');
    }
    
    public function getConfig($name = 'default') {
        return $this->rb->getConfig($name);
    }
    
    public function selectDatabase($name = 'default') {
        $this->rb->select($name);
    }
    
    public function checkSchema() {
        //R::debug(true);
        $sql = "SELECT c.relname FROM pg_catalog.pg_class c
        LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
        WHERE c.relkind IN ('r','') AND n.nspname NOT IN ('pg_catalog', 'pg_toast')
        AND pg_catalog.pg_table_is_visible(c.oid);";
        $result = R::getAll($sql);
        return $result;
    }
    
    public function setVersion($num) {
        $bean = R::findOne('application', ' key = ? ', array('version'));
        if (empty($bean)) $bean = R::dispense ('application');
        $bean->key = 'version';
        $bean->version = $num;
        R::store($bean);
    }
    
    public function getVersion() {
        try {
            $bean = R::findOne('application', ' key = ? ', array('version'));
            if (empty($bean)) throw new Exception('Database version not found!');
            return $bean->version;
        }
        catch(Exception $e) {
        }
        return false;
    }
    
    public function create($type)
    {
        return R::dispense($type);
    }
    
    public function save(&$bean)
    {
        if (!empty($bean->last_update)) $bean->last_update = date('Y-m-d H:i:s');
        return R::store($bean);
    }
    
    public function load($type, $id) {
        return R::load($type, $id);
    }
    
    public function find($type, $sql = ' 1 ', $values = array()) {
        return R::find($type, $sql, $values);
    }
    
    public function findOne($type, $sql = ' 1 ', $values = array()) {
        return R::findOne($type, $sql, $values);
    }
    
    public function related($bean, $type, $sql = ' true ', $values = array()) {
        return R::related($bean, $type, $sql, $values);
    }
    
    public function addTags($bean, $tags) {
        return R::addTags($bean, $tags);
    }
    
    public function setTags($bean, $tags) {
        return R::tag($bean, $tags);
    }
    
    public function getTags($bean) {
        return R::tag($bean);
    }
    
    public function findByTags($type, $tags) {
        return R::tagged( $type, $tags );
    }
    
    public function delete($type, $ids) {
        $beans = R::batch($type, $ids);
        foreach ($beans as $bean) R::trash($bean);
    }
    
    public function loadAll($type, $ids) {
        return R::batch($type, $ids);
    }
    
    public function exportAll($beans, $parents) {
        return R::exportAll($beans, $parents);
    }
    
    public function exec($sql, $values = array()) {
        return R::exec($sql, $values);
    }
    
    public function getAll($sql, $values = array()) {
        return R::getAll($sql, $values);
    }
    
    public function getRow($sql, $values = array()) {
        return R::getRow($sql, $values);
    }
    
    public function isOwner($account, $entity, $id) {
        $bean = $this->load($entity, $id);
        if ($account == $bean->fetchAs('account')->owner) return true;
        return false;
    }
    
    public function createPDO($dsn, $user, $pass, $silent = false) {
        $pdo = new PDO($dsn, $user, $pass);
        if ($silent) $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        return $pdo;
    }
    
    public function pdoExec($pdo, $sql) {
        return $pdo->exec($sql);
    }
    
    public function isOnGroup($account, $entity, $id) {
        $result = false;
        $groups = $account->sharedGroup;
        if (empty($groups)) return $result;
        $bean = $this->load($entity, $id);
        if (empty($bean)) return $result;
        $owner = $bean->fetchAs('account')->owner;
        if (empty($owner)) return $result;
        $tgroups = $owner->sharedGroup;
        if (empty($tgroups)) return $result;
        foreach ($tgroups as $tgroup) {
            foreach ($groups as $group) {
                if ($tgroup == $group) return true;
            }
        }
        return $result;
    }
    
    public function install($debug = false) {
        
        /**
         * Load models
         */
        $this->load->model('account/account_model');
        $this->load->model('account/group_model');
        $this->load->model('account/uriresource_model');
        $this->load->model('layout/layout_model');
        $this->load->model('admin/modmenu_model');
        $this->load->model('map_model');
        $this->load->model('layer_model');
        $this->load->model('mapserver/mapserver_model');
        $this->load->model('googleearth/googleearth_model');
        $this->load->model('openlayers/openlayers_model');
        $this->load->model('admin/dataexplorer_model');
        
        // Debug SQL
        R::debug($debug);
        
        // EMPTY DATABASE
        R::nuke();
        
        // Create admin account
        $account_admin = $this->account_model->create('admin@domain.tld', 'admin', 'admin');
        R::store($account_admin);
        
        // Create guest account
        $account_guest = $this->account_model->create('guest@domain.tld', 'guest', '');
        R::store($account_guest);
        
        // Create geo account
        $account_geo = $this->account_model->create('geo@domain.tld', 'geo', 'geo');
        R::store($account_geo);
        
        // Create admin group
        $group_admin = $this->group_model->create('admin');
        R::store($group_admin);
        
        // Create guest group
        $group_guest = $this->group_model->create('guest');
        R::store($group_admin);
        
        // Create geo group
        $group_geo = $this->group_model->create('geo');
        R::store($group_geo);
        
        // Add admin account to admin group
        $group_admin->sharedAccount[] = $account_admin;
        R::store($group_admin);
        
        // Add guest account to guest group
        $group_guest->sharedAccount[] = $account_guest;
        R::store($group_guest);
        
        // Add geo account to geo group
        $group_geo->sharedAccount[] = $account_geo;
        R::store($group_geo);
        
        // Create Uri Resources
        $uriresource_admin = $this->uriresource_model->create('/^admin/i');
        R::store($uriresource_admin);
        $uriresource_user = $this->uriresource_model->create('/^user/i');
        R::store($uriresource_user);
        
        // Add permission to guest group
        $this->group_model->addPermission($group_guest, $uriresource_user, 'deny', 0);
        $this->group_model->addPermission($group_guest, $uriresource_admin, 'deny', 0);
        R::store($group_guest);
        
        // Register authentication module block
        $previewimg = 'web/images/module/simpleauth.png';
        $authblock = $this->layout_model->createModule('Simple Authentication', 'account/modauth_lblock', NULL, $previewimg);
        $authblock->owner = $account_admin;
        R::store($authblock);
        
        // Register tickets block
        $previewimg = 'web/images/module/tickets.png';
        $ticketsblock = $this->layout_model->createModule('Tickets', 'crm/tickets_lblock', NULL, $previewimg);
        $ticketsblock->owner = $account_admin;
        R::store($ticketsblock);
        
        // Register layerswitcher block
        $previewimg = 'web/images/module/layerswitcher.png';
        $layerswitcherblock = $this->layout_model->createModule('Layer Switcher', 'openlayers/layerswitcher_lblock', 'lblock', $previewimg);
        $layerswitcherblock->owner = $account_admin;
        R::store($layerswitcherblock);
        
        // Register Footer Module
        $footermod = $this->layout_model->createModule('Footer', 'admin/footer_lblock');
        $footermod->owner = $account_admin;
        R::store($footermod);
        
        // Register Credits Module
        $creditsmod = $this->layout_model->createModule('Credits', 'admin/credits_lblock');
        $creditsmod->owner = $account_admin;
        R::store($creditsmod);
        
        // Register language switcher module block
        $previewimg = 'web/images/module/idiomswitcher.png';
        $langblock = $this->layout_model->createModule('Language Selection', 'crm/lang_lblock', NULL, $previewimg);
        $langblock->owner = $account_admin;
        R::store($langblock);
        
        // Register featuresearch block
        $previewimg = 'web/images/module/featuresearch.png';
        $searchblock = $this->layout_model->createModule('Feature Search', 'openlayers/featuresearch_lblock', 'lblock', $previewimg);
        $searchblock->owner = $account_admin;
        R::store($searchblock);
        
        // Register Google Maps Api module
        $modgmapsapi = $this->layout_model->createModule('Load Google Maps API', 'openlayers/modgmapsapiv3_lblock');
        $modgmapsapi->owner = $account_admin;
        R::store($modgmapsapi);
        
        // Register gefeaturesearch block
        $previewimg = 'web/images/module/gefeaturesearch.png';
        $gesearchblock = $this->layout_model->createModule('Google Earth Search', 'googleearth/gefeaturesearch_lblock', 'lblock', $previewimg);
        $gesearchblock->owner = $account_admin;
        R::store($gesearchblock);
        
        // Create layout
        $layout_public = $this->layout_model->create('public', 'layout/publicfullscreen2');
        $layout_public->owner = $account_admin;
        R::store($layout_public);
        
        // Create public layout slots
        $pslot1 = $this->layout_model->createSlot('slot1', $layout_public);
        $pslot2 = $this->layout_model->createSlot('slot2', $layout_public);
        $pslot3 = $this->layout_model->createSlot('slot3', $layout_public);
        $pslot4 = $this->layout_model->createSlot('slot4', $layout_public);
        $pslot5 = $this->layout_model->createSlot('slot5', $layout_public);
        $pslot1->owner = $account_admin;
        $pslot2->owner = $account_admin;
        $pslot3->owner = $account_admin;
        $pslot4->owner = $account_admin;
        $pslot5->owner = $account_admin;
        R::storeAll(array($pslot1, $pslot2, $pslot3, $pslot4, $pslot5));
        
        // Create module layout
        $layout_mod = $this->layout_model->create('module', 'layout/module');
        $layout_mod->owner = $account_admin;
        R::store($layout_mod);
        
        // Create module layout slots
        $mslot1 = $this->layout_model->createSlot('slot1', $layout_mod);
        $mslot2 = $this->layout_model->createSlot('slot2', $layout_mod);
        $mslot3 = $this->layout_model->createSlot('slot3', $layout_mod);
        $mslot1->owner = $account_admin;
        $mslot2->owner = $account_admin;
        $mslot3->owner = $account_admin;
        R::storeAll(array($mslot1, $mslot2, $mslot3));
        
        // Create layout blocks
        $lblock2 = $this->layout_model->createBlock('authblock', $authblock, 1);
        $lblock2->owner = $account_admin;
        R::storeAll(array($lblock2));
        
        // Create tickets layout block
        $lblock3 = $this->layout_model->createBlock('tickets', $ticketsblock, 2);
        $lblock3->owner = $account_admin;
        R::storeAll(array($lblock3));
        
        // Create layerswitcher layout block
        $lblock4 = $this->layout_model->createBlock('layerswitcher1', $layerswitcherblock, 3, '[]', 15);
        $lblock4->owner = $account_admin;
        R::storeAll(array($lblock4));
        
        // Create layout blocks
        $footerblock = $this->layout_model->createBlock('footerblock', $footermod, 1);
        $footerblock->owner = $account_admin;
        $creditsblock = $this->layout_model->createBlock('creditsblock', $creditsmod, 2);
        $creditsblock->owner = $account_admin;
        R::storeAll(array($footerblock, $creditsblock));
        
        // Create language blocks
        $lblock5 = $this->layout_model->createBlock('langblock', $langblock, 3);
        $lblock5->owner = $account_admin;
        R::storeAll(array($lblock5));
        
        // Assign layout blocks to slots
        $this->layout_model->slotAddBlock($pslot2, $lblock3);
        $this->layout_model->slotAddBlock($pslot2, $lblock2);
        $this->layout_model->slotAddBlock($pslot4, $footerblock);
        $this->layout_model->slotAddBlock($pslot5, $creditsblock);
        $this->layout_model->slotAddBlock($pslot2, $lblock5);
        
        // Create registered layout
        $layout_reg = $this->layout_model->create('registered', 'layout/registered');
        $layout_reg->owner = $account_admin;
        R::store($layout_reg);
        
        // Create registered layout slots
        $rslot1 = $this->layout_model->createSlot('slot1', $layout_reg);
        $rslot2 = $this->layout_model->createSlot('slot2', $layout_reg);
        $rslot3 = $this->layout_model->createSlot('slot3', $layout_reg);
        $rslot4 = $this->layout_model->createSlot('slot4', $layout_reg);
        $rslot5 = $this->layout_model->createSlot('slot5', $layout_reg);
        $rslot1->owner = $account_admin;
        $rslot2->owner = $account_admin;
        $rslot3->owner = $account_admin;
        $rslot4->owner = $account_admin;
        $rslot5->owner = $account_admin;
        R::storeAll(array($rslot1, $rslot2, $rslot3, $rslot4, $rslot5));
        
        // Create layout blocks
        $rlblock2 = $this->layout_model->createBlock('userauthblock', $authblock, 1);
        $rlblock2->owner = $account_admin;
        R::storeAll(array($rlblock2));
        
        // Assign layout blocks to slots
        $this->layout_model->slotAddBlock($rslot2, $rlblock2);
        
        // Create Admin Menu
        $menu1 = $this->modmenu_model->create('admin');
        $menu1->owner = $account_admin;
        $this->modmenu_model->save($menu1);
        
        // Register admin menu block
        $previewimg = 'web/images/module/menu.png';
        $menublock1 = $this->layout_model->createModule('Menu', 'admin/modmenu_lblock', 'modmenu', $previewimg);
        $menublock1->owner = $account_admin;
        R::store($menublock1);
        
        // Register WFS get feature popup module
        $previewimg = 'web/images/module/wfsgetfeature.png';
        $wfsgetfeature1 = $this->layout_model->createModule('WFSGetFeature Popup', 'openlayers/mapwfsgetfeaturepopup_lblock', 'lblock', $previewimg);
        $wfsgetfeature1->owner = $account_admin;
        R::store($wfsgetfeature1);
        
        // Register WFS get feature module
        $previewimg = 'web/images/module/wfsgetfeature.png';
        $wfsgetfeature2 = $this->layout_model->createModule('WFSGetFeature', 'openlayers/mapwfsgetfeaturecontent_lblock', 'lblock', $previewimg);
        $wfsgetfeature2->owner = $account_admin;
        R::store($wfsgetfeature2);
        
        // Register CKEditor module
        $previewimg = 'web/images/module/ckeditor.png';
        $ckeditor1 = $this->layout_model->createModule('CKEditor', 'layout/ckeditor_lblock', NULL, $previewimg);
        $ckeditor1->owner = $account_admin;
        R::store($ckeditor1);
        
        // Register rating module
        $previewimg = 'web/images/module/rating.png';
        $ratingmod1 = $this->layout_model->createModule('Rating', 'rating/rating_lblock', NULL, $previewimg);
        $ratingmod1->owner = $account_admin;
        R::store($ratingmod1);
        
        // Create rating blocks
        $ratingblock = $this->layout_model->createBlock('ratingblock', $ratingmod1, 2);
        $ratingblock->owner = $account_admin;
        R::storeAll(array($ratingblock));
        
        // Assign layout blocks to slots
        $this->layout_model->slotAddBlock($pslot3, $ratingblock);
        
        // Create admin layout
        $layout_adm = $this->layout_model->create('admin', 'layout/admin');
        $layout_adm->owner = $account_admin;
        R::store($layout_adm);
        
        // Create registered layout slots
        $aslot1 = $this->layout_model->createSlot('slot1', $layout_adm);
        $aslot2 = $this->layout_model->createSlot('slot2', $layout_adm);
        $aslot3 = $this->layout_model->createSlot('slot3', $layout_adm);
        $aslot4 = $this->layout_model->createSlot('slot4', $layout_adm);
        $aslot5 = $this->layout_model->createSlot('slot5', $layout_adm);
        $aslot1->owner = $account_admin;
        $aslot2->owner = $account_admin;
        $aslot3->owner = $account_admin;
        $aslot4->owner = $account_admin;
        $aslot5->owner = $account_admin;
        R::storeAll(array($aslot1, $aslot2, $aslot3, $aslot4, $aslot5));
        
        // Create layout blocks
        $alblock1 = $this->layout_model->createBlock('menu1', $menublock1, 1, '', $menu1->id);
        $alblock1->owner = $account_admin;
        $alblock2 = $this->layout_model->createBlock('adminauthblock', $authblock, 2);
        $alblock2->owner = $account_admin;
        R::storeAll(array($alblock1, $alblock2));
        
        // Assign layout blocks to slots
        $this->layout_model->slotAddBlock($aslot2, $alblock1);
        $this->layout_model->slotAddBlock($aslot1, $alblock2);
        
        // Create CKEditor Admin block
        $alblock3 = $this->layout_model->createBlock('ckeditorblock', $ckeditor1, 1);
        $alblock3->owner = $account_admin;
        R::storeAll(array($alblock3));
        
        // Assign CKEditor block to admin layout slot
        $this->layout_model->slotAddBlock($aslot1, $alblock3);
        
        // Create CKEditor User block
        $rlblock3 = $this->layout_model->createBlock('ckeditorblock', $ckeditor1, 1);
        $rlblock3->owner = $account_admin;
        R::storeAll(array($rlblock3));
        
        // Assign CKEditor block to user layout slot
        $this->layout_model->slotAddBlock($rslot1, $rlblock3);
        
        
        // Add admin menu items
        $menuitems[] = $this->modmenu_model->addItem('Admin Home', 'admin/admin', 1, $menu1, 1);
        $menuitems[] = $this->modmenu_model->addItem('Frontpage', '', 1, $menu1, 2);
        $menuitems[] = $this->modmenu_model->addItem('User Home', 'user/user', 1, $menu1, 3);
        $menuitems[] = $this->modmenu_model->addItem('System', 'admin/adminsystem', 1, $menu1, 4);
        foreach ($menuitems as $menuitem) $menuitem->owner = $account_admin;
        R::storeAll($menuitems);
        
        // Mapserver
        // Create metadata
        $msmetadata[] = $this->mapserver_model->createMetadata('wms_encoding');
        $msmetadata[] = $this->mapserver_model->createMetadata('wms_title');
        $msmetadata[] = $this->mapserver_model->createMetadata('wms_abstract');
        $msmetadata[] = $this->mapserver_model->createMetadata('wms_srs');
        $msmetadata[] = $this->mapserver_model->createMetadata('wms_onlineresource');
        $msmetadata[] = $this->mapserver_model->createMetadata('gml_include_items');
        $msmetadata[] = $this->mapserver_model->createMetadata('wms_extent');
        $msmetadata[] = $this->mapserver_model->createMetadata('ows_srs');
        $msmetadata[] = $this->mapserver_model->createMetadata('ows_enable_request');
        $msmetadata[] = $this->mapserver_model->createMetadata('wms_feature_info_mime_type');
        $msmetadata[] = $this->mapserver_model->createMetadata('wfs_title');
        $msmetadata[] = $this->mapserver_model->createMetadata('wfs_onlineresource');
        $msmetadata[] = $this->mapserver_model->createMetadata('wfs_abstract');
        $msmetadata[] = $this->mapserver_model->createMetadata('wfs_srs');
        $msmetadata[] = $this->mapserver_model->createMetadata('wfs_enable_request');
        $msmetadata[] = $this->mapserver_model->createMetadata('gml_featureid');
        foreach ($msmetadata as &$item) $item->owner = $account_admin;
        R::storeAll($msmetadata);
        
        // Create units
        $msunits[] = $this->mapserver_model->createUnits('dd');
        $msunits[] = $this->mapserver_model->createUnits('feet');
        $msunits[] = $this->mapserver_model->createUnits('inches');
        $msunits[] = $this->mapserver_model->createUnits('kilometers');
        $msunits[] = $this->mapserver_model->createUnits('meters');
        $msunits[] = $this->mapserver_model->createUnits('miles');
        $msunits[] = $this->mapserver_model->createUnits('nauticalmiles');
        $msunits[] = $this->mapserver_model->createUnits('percentages');
        $msunits[] = $this->mapserver_model->createUnits('pixels');
        foreach ($msunits as &$item) $item->owner = $account_admin;
        R::storeAll($msunits);
        
        // Create Layer Types
        $mslayertype[] = $this->mapserver_model->createLayerType('annotation');
        $mslayertype[] = $this->mapserver_model->createLayerType('chart');
        $mslayertype[] = $this->mapserver_model->createLayerType('circle');
        $mslayertype[] = $this->mapserver_model->createLayerType('line');
        $mslayertype[] = $this->mapserver_model->createLayerType('point');
        $mslayertype[] = $this->mapserver_model->createLayerType('polygon');
        $mslayertype[] = $this->mapserver_model->createLayerType('raster');
        $mslayertype[] = $this->mapserver_model->createLayerType('query');
        foreach ($mslayertype as &$item) $item->owner = $account_admin;
        R::storeAll($mslayertype);
        
        // Create Layer Connection Types
        $mslayerconntype[] = $this->mapserver_model->createLayerConnectionType('local');
        $mslayerconntype[] = $this->mapserver_model->createLayerConnectionType('ogr');
        $mslayerconntype[] = $this->mapserver_model->createLayerConnectionType('oraclespatial');
        $mslayerconntype[] = $this->mapserver_model->createLayerConnectionType('plugin');
        $mslayerconntype[] = $this->mapserver_model->createLayerConnectionType('postgis');
        $mslayerconntype[] = $this->mapserver_model->createLayerConnectionType('sde');
        $mslayerconntype[] = $this->mapserver_model->createLayerConnectionType('wfs');
        $mslayerconntype[] = $this->mapserver_model->createLayerConnectionType('wms');
        foreach ($mslayerconntype as &$item) $item->owner = $account_admin;
        R::storeAll($mslayerconntype);
        
        // Create a new map
        $map = $this->map_model->create('Demo Map', 'Simple map with OSM + WMS', 'demo');
        $map->owner = $account_admin;
        $this->map_model->save($map);
        
        // Create layer
        $layer = $this->layer_model->create('Demo Layer', 'Data from shapefile', 'layer1');
        $layer->owner = $account_admin;
        $this->layer_model->save($layer);
        
        // Add Layer to Map
        $this->map_model->addMapLayer($map, $layer);
        
        // Create wfsgetfeature block
        $wfsgetfeaturelblockconfig = 
'{
"layer":"layer1",
"popupfunction":"popupfeature",
"htmlurl":null
}';
        $wfsgetfeaturelblock = $this->layout_model->createBlock('wfsgetfeature1', $wfsgetfeature2, 1, $wfsgetfeaturelblockconfig, 15);
        $wfsgetfeaturelblock->owner = $account_admin;
        R::storeAll(array($wfsgetfeaturelblock));
        
        // assignwfsgetfeature block
        $this->layout_model->slotAddBlock($pslot3, $wfsgetfeaturelblock);
        
        // Create mapfile
        $extent = '-20037508.34 -20037508.34 20037508.34 20037508.34';
        $projection = "init=epsg:3857";
        $mapfile = $this->mapserver_model->createMapfile($map, $extent, $projection);
        $mapfile->msunits = $msunits[4];
        $mapfile->debug = 'off';
        $mapfile->fontset = './mapfile/fonts/fonts.list';
        $mapfile->symbolset = './mapfile/symbols/symbols.txt';
        $mapfile->owner = $account_admin;
        $this->mapserver_model->save($mapfile);
        
        // Add metadata
        $this->mapserver_model->addMapfileMetadata($mapfile, $msmetadata[0], 'UTF8');
        $this->mapserver_model->addMapfileMetadata($mapfile, $msmetadata[1], 'Cities');
        $this->mapserver_model->addMapfileMetadata($mapfile, $msmetadata[2], 'No info');
        $this->mapserver_model->addMapfileMetadata($mapfile, $msmetadata[3], 'EPSG:20790 EPSG:3857 EPSG:4326');
        $this->mapserver_model->addMapfileMetadata($mapfile, $msmetadata[4], 'mapserver?map='.$map->alias.'.map');
        $this->mapserver_model->addMapfileMetadata($mapfile, $msmetadata[8], '*');
        $this->mapserver_model->addMapfileMetadata($mapfile, $msmetadata[9], 'text/html');
        
        // Create Mapserver Layer
        $extent = '-13207.017577 49518.222243 452964.525460 291327.263653';
        $projection = "proj=tmerc lat_0=39.66666666666666 lon_0=1 k=1 x_0=200000 y_0=300000 ellps=intl towgs84=-304.046,-60.576,103.64,0,0,0,0 pm=lisbon units=m no_defs";
        $mslayer = $this->mapserver_model->createLayer($layer, $extent, $projection);
        $mslayer->mslayertype = $mslayertype[4];
        $mslayer->template = './mapfile/template/shape_feature_body.html';
        $mslayer->data = './mapfile/shapefile/map1.shp';
        $mslayer->labelitem = 'concelho';
        $mslayer->owner = $account_admin;
        
        // Add Layer Metadata
        $this->mapserver_model->addLayerMetadata($mslayer, $msmetadata[5], 'all');
        $this->mapserver_model->addLayerMetadata($mslayer, $msmetadata[7], 'EPSG:3857');
        
        $this->mapserver_model->save($mslayer);
        $this->mapserver_model->addMapfileLayer($mapfile, $mslayer);
        
        // Create Layer Class
        $msclass = $this->mapserver_model->createClass($mslayer, 'Cities');
        $msclass->owner = $account_admin;
        $this->mapserver_model->save($msclass);
        
        // Create MapIcons Point Style 
        $msstyle1 = $this->mapserver_model->createStyle('Simple Map Icon');
        $msstyle1->symbol = './mapfile/symbols/mapiconscollection-tourism/bigcity.png';
        $msstyle1->size = 32;
        $msstyle1->color = '0 255 0';
        $msstyle1->owner = $account_admin;
        $this->mapserver_model->save($msstyle1);
        
        // Add Style to Class
        $this->mapserver_model->addClassStyle($msclass, $msstyle1);
        
        // Create Default Point Style 
        $msstyle3 = $this->mapserver_model->createStyle('Simple Icon');
        $msstyle3->symbol = './mapfile/symbols/google-marker-small.png';
        $msstyle3->size = 30;
        $msstyle3->color = '0 255 0';
        $msstyle3->owner = $account_admin;
        $this->mapserver_model->save($msstyle3);
        
        // Create Default Area Style
        $msstyle2 = $this->mapserver_model->createStyle('Simple Area');
        $msstyle2->symbol = '';
        $msstyle2->owner = $account_admin;
        $this->mapserver_model->save($msstyle2);
        
        // Create Label
        $mslabel = $this->mapserver_model->createLabel('Simple Label');
        $mslabel->owner = $account_admin;
        $this->mapserver_model->save($mslabel);
        
        // Add Label to Class
        $this->mapserver_model->addClassLabel($msclass, $mslabel);
        
        // Create Map Legend
        $mslegend = $this->mapserver_model->createLegend($mapfile);
        $mslegend->template = './mapfile/template/shape_legend_body.html';
        $mslegend->owner = $account_admin;
        $this->mapserver_model->save($mslegend);
        
        // Update mapfile AFTER all mapfile is set
        $this->mapserver_model->updateMapfile($mapfile->id);
        
        // Add Label to Map Legend
        $this->mapserver_model->addLegendLabel($mslegend, $mslabel);
        
        // Register openlayers map block
        $previewimg = 'web/images/module/openlayersmap.png';
        $olmapblock = $this->layout_model->createModule('OpenLayers Map', 'openlayers/modmap_lblock', 'olmap', $previewimg);
        $olmapblock->owner = $account_admin;
        R::store($olmapblock);
        
        // Create Openlayers Layers Type
        $ollayertype[] = $this->openlayers_model->createLayerType('OSM', 'OpenLayers.Layer.OSM');
        $ollayertype[] = $this->openlayers_model->createLayerType('Google', 'OpenLayers.Layer.Google');
        $ollayertype[] = $this->openlayers_model->createLayerType('Bing', 'OpenLayers.Layer.Bing');
        $ollayertype[] = $this->openlayers_model->createLayerType('Internal WMS', 'OpenLayers.Layer.WMS');
        $ollayertype[] = $this->openlayers_model->createLayerType('External WMS', 'OpenLayers.Layer.WMS');
        $ollayertype[] = $this->openlayers_model->createLayerType('Vector + WFS', 'OpenLayers.Layer.Vector');
        foreach ($ollayertype as &$item) $item->owner = $account_admin;
        R::storeAll($ollayertype);
        
        // Create Abstract Layers
        $osmlayer = $this->layer_model->create('OSM', 'OpenStreetMap Layer', 'osm1');
        $osmlayer->owner = $account_admin;
        $this->layer_model->save($osmlayer);
        $googlelayer = $this->layer_model->create('Google', 'Google Layer', 'google1');
        $googlelayer->owner = $account_admin;
        $this->layer_model->save($googlelayer);
        $binglayer = $this->layer_model->create('Bing', 'Bing Layer', 'bing1');
        $binglayer->owner = $account_admin;
        $this->layer_model->save($binglayer);
        // Add Layers to Map
        $this->map_model->addMapLayer($map, $osmlayer);
        $this->map_model->addMapLayer($map, $googlelayer);
        $this->map_model->addMapLayer($map, $binglayer);
        
        // Create Specific OL Layers
        // Create OSM Layer
        $opts = "{\n\"isBaseLayer\": true\n}";
        $olosmlayer = $this->openlayers_model->createLayer($osmlayer, $ollayertype[0], '', $opts);
        $olosmlayer->owner = $account_admin;
        $this->openlayers_model->save($olosmlayer);
        // Create Google Layer
        $opts = "{\n\"isBaseLayer\": true\n}";
        $vendoropts = "{\n\"type\":\"satellite\",\n\"numZoomLevels\":22\n}";
        $olgooglelayer = $this->openlayers_model->createLayer($googlelayer, $ollayertype[1], '', $opts, $vendoropts);
        $olgooglelayer->owner = $account_admin;
        $this->openlayers_model->save($olgooglelayer);
        // Create Bing Layer
        $opts = "{\n\"isBaseLayer\": true\n}";
        $vendoropts = "{\n\"name\": \"".$binglayer->title."\",\n\"key\":\"AqTGBsziZHIJYYxgivLBf0hVdrAk9mWO5cQcb8Yux8sW5M8c8opEC2lZqKR1ZZXf\",\n\"type\":\"Aerial\"\n}";
        $olbinglayer = $this->openlayers_model->createLayer($binglayer, $ollayertype[2], '', $opts, $vendoropts);
        $olbinglayer->owner = $account_admin;
        $this->openlayers_model->save($olbinglayer);
        // Create WMS Mapserver Layer defined above
        $opts = "{\n\"isBaseLayer\": false,\n\"gutter\": 15\n}";
        $vendoropts = "{\n\"layers\":\"".$layer->alias."\",\n\"transparent\": true,\n\"projection\":\"EPSG:20790\"\n}";
        $url = $map->alias;
        $olwmslayer = $this->openlayers_model->createLayer($layer, $ollayertype[3], $url, $opts, $vendoropts);
        $olwmslayer->owner = $account_admin;
        $this->openlayers_model->save($olwmslayer);
        
        // Create Openlayers Map
        $olmap = $this->openlayers_model->createMap($map);
        $olmap->projection = 'EPSG:3857';
        $olmap->owner = $account_admin;
        $this->openlayers_model->save($olmap);
        
        // Create feature search block
        $lblock6 = $this->layout_model->createBlock('searchblock', $searchblock, 1, '{"layer":"layer1"}', 15);
        $lblock6->owner = $account_admin;
        R::storeAll(array($lblock6));
        
        // Add OL Layers to OL Map
        $this->openlayers_model->addMapLayer($olmap, $olosmlayer);
        //$this->openlayers_model->addMapLayer($olmap, $olgooglelayer);
        //$this->openlayers_model->addMapLayer($olmap, $olbinglayer);
        $this->openlayers_model->addMapLayer($olmap, $olwmslayer);
        
        // Create publicmap layout block
        $olmapblockconfig =
'{
"run":["layerswitcher1","wfsgetfeature1","searchblock"],
"center":[-8.5,38.58],
"zoom":8
}';
        $lblock5 = $this->layout_model->createBlock('publicmap1', $olmapblock, 1, $olmapblockconfig, $olmap->id);
        $lblock5->owner = $account_admin;
        R::storeAll(array($lblock5));
        
        // Add publicmap block to public layout slot
        $this->layout_model->slotAddBlock($pslot1, $lblock5);
        
        
        // Add Links to Mapserver Administration
        $menuitems[] = $this->modmenu_model->addItem("Explorer", 'admin/dataexplorer', 1, $menu1, 5);
        $menuitems[] = $this->modmenu_model->addItem("Tickets", 'admin/adminticket', 1, $menu1, 6);
        $menuitems[] = $this->modmenu_model->addItem("Maps", 'admin/adminmap', 1, $menu1, 7);
        $menuitems[] = $this->modmenu_model->addItem("Layers", 'admin/adminlayer', 1, $menu1, 8);
        $menuitems[] = $this->modmenu_model->addItem("Places", 'admin/adminpgplace', 1, $menu1, 9);
        $menuitems[] = $this->modmenu_model->addItem("MapServer Options", 'admin/adminmapserver', 1, $menu1, 10);
        $menuitems[] = $this->modmenu_model->addItem("OpenLayers Options", 'admin/adminopenlayers', 1, $menu1, 11);
        $menuitems[] = $this->modmenu_model->addItem("Google Earth Options", 'admin/admingoogleearth', 1, $menu1, 12);
        $menuitems[] = $this->modmenu_model->addItem("Import", 'admin/import', 1, $menu1, 13);
        foreach ($menuitems as &$item) $item->owner = $account_admin;
        R::storeAll($menuitems);
        
        // Register default files
        $this->dataexplorer_model->registerpath('./mapfile', 'private', $account_admin);
        
        // Create User Menu
        $menu2 = $this->modmenu_model->create('usermenu');
        $menu2->owner = $account_admin;
        $this->modmenu_model->save($menu2);
        
        // Add user menu items
        $menu2items[] = $this->modmenu_model->addItem('Home', 'user/user', 1, $menu2, 1);
        $menu2items[] = $this->modmenu_model->addItem('Frontpage', '', 1, $menu2, 2);
        $menu2items[] = $this->modmenu_model->addItem('My Maps', 'user/managemap', 1, $menu2, 3);
        $menu2items[] = $this->modmenu_model->addItem('My Layers', 'user/managelayer', 1, $menu2, 4);
        $menu2items[] = $this->modmenu_model->addItem('My Places', 'user/managepgplace', 1, $menu2, 5);
        $menu2items[] = $this->modmenu_model->addItem('My Styles', 'user/managemsstyle', 1, $menu2, 6);
        $menu2items[] = $this->modmenu_model->addItem('My Tickets', 'user/manageticket', 1, $menu2, 7);
        $menu2items[] = $this->modmenu_model->addItem("Import", 'user/userimport', 1, $menu2, 8);
        foreach ($menu2items as &$item) $item->owner = $account_admin;
        R::storeAll($menu2items);
        
        // Create user menu block
        $rlblock3 = $this->layout_model->createBlock('usermenu1', $menublock1, 1, '', $menu2->id);
        $rlblock3->owner = $account_admin;
        R::storeAll(array($rlblock3));
        
        // Assign layout blocks to slots
        $this->layout_model->slotAddBlock($rslot3, $rlblock3);
        
        // Create full screen layout
        $fullscreenedit_public = $this->layout_model->create('fullscreenedit', 'admin/fullscreenedit');
        $fullscreenedit_public->owner = $account_admin;
        R::store($fullscreenedit_public);
        
        // Assign blocks to public layout
        $this->layout_model->slotAddBlock($pslot3, $lblock6);
        $this->layout_model->slotAddBlock($pslot3, $lblock4);
        
        // Register public controllers
        $ctl = $this->create('controller');
        $ctl->path = 'publicmap';
        $ctl->layout = $layout_public;
        $this->save($ctl);
        $ctl = $this->create('controller');
        $ctl->path = 'auth';
        $ctl->layout = $layout_mod;
        $this->save($ctl);
        $ctl = $this->create('controller');
        $ctl->path = 'tickets';
        $ctl->layout = $layout_mod;
        $this->save($ctl);
        
        // Register Google Earth map moduke
        $previewimg = 'web/images/module/openlayersmap.png';
        $gemapmod = $this->layout_model->createModule('Google Earth Map', 'googleearth/modgemap_lblock', 'gemap', $previewimg);
        $gemapmod->owner = $account_admin;
        R::store($gemapmod);
        
        // Create Google Earth Layer Type
        $gelayertype[] = $this->googleearth_model->createLayerType('KML');
        foreach ($gelayertype as &$item) $item->owner = $account_admin;
        R::storeAll($gelayertype);
        
        // Set Application version (should be last instruction)
        $this->setVersion($this->config->item('_version'));
            
        // Return success
        return true;
    }
    
}

?>
