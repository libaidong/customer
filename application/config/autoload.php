<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$autoload['packages'] = array();
$autoload['libraries'] = array('database', 'site', 'assets', 'session');
$autoload['helper'] = array('url', 'form', 'inflector');
$autoload['config'] = array('assets');
$autoload['language'] = array();
$autoload['model'] = array('Security', 'User', 'Log');
