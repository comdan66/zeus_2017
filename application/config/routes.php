<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Route::root ('main');

// $route['admin'] = "admin/main";
Route::get ('admin', 'admin/main@index');

Route::get ('/login', 'platform@login');
Route::get ('/logout', 'platform@logout');
Route::get ('/platform/index', 'platform@login');
Route::get ('/platform', 'platform@login');












Route::group ('admin', function () {
  Route::get ('/users/(:id)/show', 'users@show($1)');
  Route::get ('/users/(:id)/show/(:any)', 'users@show($1, $2)');
  Route::get ('/users/(:id)/show/(:any)/(:num)', 'users@show($1, $2, $3)');

  Route::get ('/calendar', 'main@calendar');
  Route::get ('/my', 'main@index');
  Route::get ('/my/(:any)', 'main@index($1)');
  Route::get ('/my/(:any)/(:num)', 'main@index($1, $2)');

  Route::resourcePagination (array ('schedule_tags'), 'schedule_tags');
  Route::resourcePagination (array ('users'), 'users');
  Route::resourcePagination (array ('banners'), 'banners');
  Route::resourcePagination (array ('promos'), 'promos');
  Route::resourcePagination (array ('article_tags'), 'article_tags');
  Route::resourcePagination (array ('articles'), 'articles');

  Route::resourcePagination (array ('work_tags'), 'work_tags');
  Route::resourcePagination (array ('tag', 'work_tags'), 'tag_work_tags');
  Route::resourcePagination (array ('works'), 'works');
  
  Route::resourcePagination (array ('invoice_tags'), 'invoice_tags');
  Route::resourcePagination (array ('invoice_contacts'), 'invoice_contacts');
  Route::resourcePagination (array ('contact', 'invoice_contacts'), 'contact_invoice_contacts');
  Route::resourcePagination (array ('invoices'), 'invoices');
});

Route::group ('api', function () {
  Route::resource (array ('schedules'), 'schedules');
  Route::resource (array ('schedule_tags'), 'schedule_tags');
});


// $route['main/index/(:num)/(:num)'] = "main/aaa/$1/$2";
// Route::get ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// Route::post ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// Route::put ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// Route::delete ('main/index/(:num)/(:num)', 'main@aaa($1, $2)');
// Route::controller ('main', 'main');
  // whit get、post、put、delete prefix

/* End of file routes.php */
/* Location: ./application/config/routes.php */