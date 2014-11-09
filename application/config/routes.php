<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$route['default_controller'] = "pages";
$route['404_override'] = '';




$route['login'] = 'CustomerLogin_C/index';
$route['logout'] = 'CustomerLogin_C/logout';
$route['mainLogin'] = 'CustomerLogin_C/login';
$route['indexMain'] = 'CustomerLogin_C/indexMain';
$route['passwordUpdate'] = 'CustomerLogin_C/passwordUpdate';
$route['passwordIndex'] = 'CustomerLogin_C/passwordIndex';
$route['registrationIndex'] = 'CustomerRegister_C/index';
$route['registrationInsertInfo'] = 'CustomerRegister_C/insertInfo';
$route['registrationUpdateInfo'] = 'CustomerRegister_C/updateInfo';
$route['registrationSelectInfo'] = 'CustomerRegister_C/selectInfo';

$route['task'] = 'trackprogress/index';
$route['taskEdit'] = 'trackprogress/edit';

$route['calendar'] = 'Common/calendar';
$route['mailcompose'] = 'Common/mailcompose';
$route['maildetail'] = 'Common/maildetail';
$route['mailbox'] = 'Common/mailbox';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
