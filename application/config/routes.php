<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['admin'] = "cms/admin";
$route['admin/logout'] = "cms/admin/logout";
$route['admin/content/(:any)'] = "cms/admin/content/$1";
//$route['admin/(:any)'] = "cms/admin/$1";

$route['adminlogin'] = "cms/adminlogin";
$route['adminlogin/(:any)'] = "cms/adminlogin/$1";

$route['admin/task/(:any)'] = "util/task/$1";
$route['admin/import/(:any)'] = "util/import/$1";
$route['social/(:any)'] = "util/social/$1";
$route['ajax/(:any)'] = "api/ajax/$1";

$route['adminajax/(:any)'] = "api/adminajax/$1";

$route['setup'] = 'util/setup';
$route['setup/(:any)'] = 'util/setup/$1';

$route['default_controller'] = "page";
$route['404_override'] = 'page';

/* End of file routes.php */
/* Location: ./application/config/routes.php */