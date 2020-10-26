<?php

set_include_path( dirname(__DIR__) );

#########################################################################################

define('MAINDIR', get_include_path() . DS . 'app' . DS . 'main');

$path = $_SERVER['REQUEST_URI'];

$dir = explode('/public_html/', $path);

array_shift($dir);

$dir = explode('/', $dir[0]);
$dir = ! empty($dir[0]) ? get_include_path() . DS . 'app' . DS . $dir[0] : MAINDIR;

$appFolder = is_dir($dir) ? $dir . DS : MAINDIR . DS;

define('BASEPATH', $appFolder);
define('VIEWSPATH', BASEPATH . 'Views' . DS);
define('VIEW_TEMPLATES', VIEWSPATH . 'templates' . DS);

if ( isset($_SERVER['ENVIRONMENT']) )
	define('ENVIRONMENT', $_SERVER['ENVIRONMENT']);

// Path to the front controller (this file)
define('FCPATH', get_include_path() . DS);

if ( ENVIRONMENT !== 'production')
{
	if ( ! is_dir(BASEPATH) ) {
		exit('Você deve criar o diretório de aplicação em <strong>' . BASEPATH . '</strong>');
	}

	if ( ! is_dir(VIEWSPATH) ) {
		exit('Não foi possível localizar o diretório <strong>' . VIEWSPATH . '</strong>.');
	}

	if ( ! is_dir(VIEW_TEMPLATES) ) {
		exit('Não foi possível localizar o diretório <strong>' . VIEW_TEMPLATES . '</strong>.');
	}
}

#########################################################################################