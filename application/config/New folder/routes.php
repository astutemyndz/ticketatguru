<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'Home/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
/*
    This route for frontend
*/
$route['landing']                                   = 'Home/landing';
$route['set-age']                                   = 'Home/set_age';
$route['signup']                                    = 'Home/signup';
$route['login']                                     = 'Home/login';
$route['do-registration']                           = 'Home/do_registration';
$route['do-login']                                  = 'Home/do_login';
$route['logout']                                    = 'Home/logout';
$route['profile']                                   = 'Home/profile';
$route['video-chat']                                = 'Home/video_chat';
$route['profile-update']                            = 'Home/profile_update';
$route['personal-details']                          = 'Home/personal_details';
$route['verification']                              = 'Home/verification';
$route['performer/(:any)/(:any)']                   = 'Home/view_profile/$1/$2/';
$route['performer/(:any)/(:any)/(:any)']            = 'Home/view_profile/$1/$2/$3';
$route['performer-profile/(:any)/(:any)']           = 'Home/view_profile/$1/$2/';
$route['performer-profile/(:any)/(:any)/(:any)']    = 'Home/view_profile/$1/$2/$3';
$route['subs-cribe']                                = 'Home/subscribe';
/*
    This route for admin
*/
$route['admin']                                 = 'admin/Admin/index';
$route['admin/login']                           = 'admin/Admin/doLogin';
$route['admin/dashboard']                       = 'admin/Admin/dashboard';
$route['admin/logout']                          = 'admin/Admin/doLogout';
$route['admin/change/password']                 = 'admin/Admin/change_password';
$route['admin/profile']                         = 'admin/Admin/profile';
$route['admin/forgot/password']                 = 'admin/Admin/forget_password';
$route['admin/categories']                      = 'admin/Services/category_listing';
$route['admin/category/add_category']           = 'admin/Services/add_category';
$route['admin/credit/plans']                    = 'admin/settings/credit_plan';
$route['admin/users/list/(:any)']               = 'admin/Services/users/$1';
$route['admin/users/add-user']                  = 'admin/Services/add_user';
$route['admin/users/edit-user/(:any)']          = 'admin/Services/add_user/$1';
$route['admin/verification/performer']          = 'admin/Services/verify_performer';
$route['admin/user/details/(:any)']             = 'admin/Services/user_details/$1';
