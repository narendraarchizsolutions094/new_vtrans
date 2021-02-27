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

$route['default_controller']      = 'home'; 

$route['login']                   = 'dashboard';

$route['logout']                  = 'dashboard/logout';



$route['slider/(:num)']           = 'home/slider/$1';

$route['slider/(:num)/(:any)']    = 'home/slider/$1';



$route['details/(:num)']          = 'home/details/$1';

$route['details/(:num)/(:any)']   = 'home/details/$1'; 



$route['patient_info/(:any)']     = 'website/patient/profile/$1';

$route['appointment_info/(:any)'] = 'website/appointment/preview/$1';





/*---------user start ------*/

$route['main-function'] = 'dash/area_list';

$route['add-main-function'] = 'dash/area_add';

$route['edit-mainfun/(:any)'] = 'dash/edit_mainfun/$1';

//$route['edit_user/(:any)'] = 'dash/edit_user/$1';

//$route['edit_user'] = 'dash/edit_user';

//$route['active_user'] = 'dash/active_user';

//$route['deactive_user'] = 'dash/deactive_user';

//$route['check_dublicate'] = 'dash/check_dublicate_user';

/*---------user start ------*/



/*---------user start ------*/

$route['floor-list'] = 'dash/floor_list';

$route['floor-add'] = 'dash/floor_add';

$route['edit-floor/(:any)'] = 'dash/edit_floor/$1';

//$route['edit_user/(:any)'] = 'dash/edit_user/$1';

//$route['edit_user'] = 'dash/edit_user';

//$route['active_user'] = 'dash/active_user';

//$route['deactive_user'] = 'dash/deactive_user';

//$route['check_dublicate'] = 'dash/check_dublicate_user';

/*---------user start ------*/

/*---------user start ------*/

$route['room-list'] = 'dash/room_list';
$route['enquiry'] = 'enq';
$route['room-add'] = 'dash/room_add';

$route['edit-room/(:any)'] = 'dash/edit_room/$1';

//$route['edit_user/(:any)'] = 'dash/edit_user/$1';

//$route['edit_user'] = 'dash/edit_user';

//$route['active_user'] = 'dash/active_user';

//$route['deactive_user'] = 'dash/deactive_user';

//$route['check_dublicate'] = 'dash/check_dublicate_user';

/*---------user start ------*/





/*---------expensehead Company ------*/

$route['category-list'] = 'dash/expensehead_list';

$route['category-add'] = 'dash/expensehead_add';

$route['check_dublicate_exp'] = 'dash/check_dublicate_exp';

$route['category-edit'] = 'dash/edit_headlist';

$route['category-edit/(:any)'] = 'dash/edit_headlist/$1';

$route['active_expense'] = 'dash/active_expense';

$route['deactive_enpense'] = 'dash/deactive_enpense';



/*---------End Company ------*/



/*---------start item list------*/

$route['function-list'] = 'dash/item_list';

$route['add-function'] = 'dash/add_item';

$route['edit-function/(:any)'] = 'dash/edit-item/$1';

$route['edit-functions/(:any)'] = 'dash/edit_items/$1';

$route['active_function'] = 'dash/active_item';

$route['deactive_function'] = 'dash/deactive_item';

$route['deactive_product'] = 'dash/deactive_products';



$route['sub-function-list'] = 'dash/sub_list';

$route['add-sub-function'] = 'dash/add_sub_function';

$route['sub-function-delete/(:any)'] = 'dash/delete_sub_function/$1';

$route['edit-sub-functions/(:any)'] = 'dash/edit_sub_functions/$1';

/*---------End item ------*/



/*---------start party list------*/

$route['boq-reports'] = 'reports';

$route['load_reports/(:any)'] = 'reports/reports_baq/$1';



/*---------End party ------*/



//site Rediness sheet mail template...



$route['Site-rediness-sheet/(:any)']='Installationprocess/send_rediness_mail/$1';

$route['installation-dates/(:any)'] = 'Installationprocess/readiness_confirmation_date/$1';



//change password....

$route['change-password/(:any)'] = 'dashboard/send_change_password_link/$1';

//admin routes.
$route['customer/logged-in-as-user/(:any)'] = 'customer/logged_in_as_user/$1';
$route['customer/update-billing-date'] = 'customer/update_billing_date';

//inactive all users(users)
$route['user/inactive-all'] = 'user/inactiveAlluser';
$route['edit-userrights/(:any)']   = 'userrights/userrights_edit/$1'; 
// master lead search
$route['document-templates'] = 'setting/document_templates';
$route['master_lead_search'] = 'lead/lead_search';
$route['lead'] = 'led';
// $route['feedback'] = 'Dashboard/feedback';
// $route['feedback-edit/(:any)'] = 'Dashboard/edit_feedback/$i';
// $route['feedback/create'] = 'Dashboard/create';
// $route['feedback/insert'] = 'Dashboard/insert';
$route['facebook-pages'] = 'dashboard/facbookData';
$route['report-view/:any/:any'] = 'report/send_sales_view/$i/$i';
$route['visits/visit_details/:any'] = 'client/visit_details/$i';
$route['visit/vist-report'] = 'client/report/';
$route['setting/expense-master'] = 'setting/expenseMaster/';
$route['visit-expense/delete/:any'] = 'setting/visit_expense_delete/$i';

$route['visits/visit_map/:any'] = 'dashboard/visit_map_only/$i';

$route['404_override']            = '';

$route['translate_uri_dashes']    = true;

