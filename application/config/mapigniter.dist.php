<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
*/

/*
 * This is where you put your private files (files that do not need to be 
 * available to the browser).
 * Normally, this can be MapServer resources like shapefiles, fonts or icons
 * Also, imported shapefiles will use this directory
 */
$config['private_data_path'] = '/var/www/mapigniter/data/';

/*
 * This is where you put files that can be available to the browser, like icons,
 * images, video
 */
$config['public_data_path'] = '/var/www/mapigniter/web/data/';

/*
 * Temporary directory for cached MapServer image requests
 * This is used by admin/Filecache Model
 */
$config['cache_file_path'] = '/var/www/mapigniter/data/cache/';

/*
 * Full path to MapServer cgi executable
 */
$config['mapserver_path'] = '/usr/lib/cgi-bin/mapserv';

/*
 * Full url to MapServer cgi
 */
$config['mapserver_cgi'] = 'http://localhost/cgi-bin/mapserv?';

/*
 * Public Ticket module
 * Email origin used by ticket module
 */
$config['ticket_email_origin'] = 'marcoafonso@marcoafonso.pt';

/*
 * Public ticket module
 * Email sender
 */
$config['ticket_email_name'] = 'Marco Afonso';

/*
 * Public ticket module
 * Default email subject
 */
$config['ticket_email_subject'] = 'MapIgniter';

/*
 * Cache expire time
 * This is used by Filecache Model
 */
$config['cache_expire'] = 300;

/*
 * Use cache
 * This tell whether MapServer image requests should be cached or not
 * You can see information under Menu Administration -> System -> Cache
 */
$config['cache_on'] = 0;

/*
 * Version identifier
 * Major: 1 digit
 * Minor: 2 digits 
 * Bugfix: 3 digits
 * 
 * This will be saved into database
 */
$config['_version'] = 101000;
