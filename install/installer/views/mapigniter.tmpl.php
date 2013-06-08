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
$config['private_data_path'] = '{{private_data_path}}';

/*
 * This is where you put files that can be available to the browser, like icons,
 * images, video
 */
$config['public_data_path'] = '{{public_data_path}}';

/*
 * Temporary directory for cached MapServer image requests
 * This is used by admin/Filecache Model
 */
$config['cache_file_path'] = '{{cache_file_path}}';

/*
 * Full path to MapServer cgi executable
 */
$config['mapserver_path'] = '{{mapserver_path}}';

/*
 * Full url to MapServer cgi
 */
$config['mapserver_cgi'] = '{{mapserver_cgi}}';

/*
 * Full path to psql executable
 */
$config['psql_path'] = '{{psql_path}}';

/*
 * Full path to shp2pgsql executable
 */
$config['shp2pgsql_path'] = '{{shp2pgsql_path}}';

/*
 * Public Ticket module
 * Email origin used by ticket module
 */
$config['ticket_email_origin'] = '{{ticket_email_origin}}';

/*
 * Public ticket module
 * Email sender
 */
$config['ticket_email_name'] = '{{ticket_email_name}}';

/*
 * Public ticket module
 * Default email subject
 */
$config['ticket_email_subject'] = '{{ticket_email_subject}}';

/*
 * Cache expire time
 * This is used by Filecache Model
 */
$config['cache_expire'] = {{cache_expire}};

/*
 * Use cache
 * This tell whether MapServer image requests should be cached or not
 * You can see information under Menu Administration -> System -> Cache
 */
$config['cache_on'] = {{cache_on}};

/*
 * Version identifier
 * Major: 1 digit
 * Minor: 2 digits 
 * Bugfix: 3 digits
 * 
 * This will be saved into database
 */
$config['_version'] = 101000;
