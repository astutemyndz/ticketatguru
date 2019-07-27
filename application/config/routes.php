<?php

defined('BASEPATH') OR exit('No direct script access allowed');



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

|	https://codeigniter.com/user_guide/general/routing.html

|

| -------------------------------------------------------------------------

| RESERVED ROUTES

| -------------------------------------------------------------------------

|

| There are three reserved routes:

|

|	$route['default_controller'] = 'welcome';

|

| This route indicates which controller class should be loaded if the

| URI contains no data. In the above example, the "welcome" class

| would be loaded.

|

|	$route['404_override'] = 'errors/page_missing';

|

| This route will tell the Router which controller/method to use if those

| provided in the URL cannot be matched to a valid route.

|

|	$route['translate_uri_dashes'] = FALSE;

|

| This is not exactly a route, but allows you to automatically route

| controller and method names that contain dashes. '-' isn't a valid

| class or method name character, so it requires translation.

| When you set this option to TRUE, it will replace ALL dashes in the

| controller and method URI segments.

|

| Examples:	my-controller/index	-> my_controller/index

|		my-controller/my-method	-> my_controller/my_method

*/

$route['default_controller'] = 'EventController/pjActionEvents';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['ajaxCity'] = 'home/ajaxCity';
$route['location'] = 'home/location';
$route['admin'] = 'login';
$route['admin/login'] = 'login';
$route['admin/logout'] = 'logout';
$route['job/(:any)'] = 'job/listings';







$route['event/details/(:any)'] = 'EventController/pjActionDetails/$1';
$route['event/pjActionSeatsAjax'] = 'EventController/pjActionSeatsAjax';
$route['event/pjActionSaveSeats'] = 'EventController/pjActionSaveSeats';
$route['event/cart'] = 'EventController/pjActionCart';

$route['event/seats'] = 'EventController/pjActionSeats';
//$route['event/(:any)'] = ''
$route['welcome'] = 'welcome';

$route['set'] = 'EventController/setSessionData';
$route['get'] = 'EventController/getSessionData';


//Get Login Form
$route['auth/login'] = 'AuthController/pjAuthForm';
// Post Login
$route['auth/login/post'] = 'AuthController/login';

//Post Logout 
$route['auth/logout'] = 'AuthController/logout';

// Post Register
$route['auth/register/post'] = 'AuthController/register';

$route['account'] = 'AccountController/pjAccountForm';