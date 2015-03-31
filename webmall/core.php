<?php

session_start();
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ob_start();

define('SYS_STARTED', true);
define('SYS_ROOT', realpath(dirname(__FILE__)) . '/');
define('SYS_USER', SYS_ROOT . 'user');
define('SYS_SYSTEM', SYS_ROOT . 'system');

require(SYS_ROOT . 'config.php');

load_source('sessions', 'system', 'security', 'forms', 'encryption', 'pagination');

$conn = mssql_connect($mssql_config['server'], $mssql_config['user'], $mssql_config['password']) or die('mssql connection error');
$mysql_db = mysql_connect($mysql_config['host'], $mysql_config['user'], $mysql_config['pass']);
mysql_select_db($mysql_config['db_name'], $mysql_db);

if (safe_get($_GET)) die('Security activated');
	
$user_is_logged = false;
if (is_logged('user')) {
	$query = mssql_query("SELECT * FROM PS_UserData.dbo.Users_Master WHERE UserID = '" . read_session('username') . "'");
	$user_data = mssql_fetch_array($query);
	$user_is_logged = true;
}

$admin_is_logged = false;
if (is_logged('admin')) $admin_is_logged = true;

$validate = get_url_param('validate');

if ($validate) {
	$validation_path = 'user';
	
	if (_basename(current_url()) == 'admin.php') {
		$validation_path = 'admin';
		
		if ($validate != 'login') {
			// patikrinam ar administratorius prisijunges
			if (!is_logged('admin')) redirect('admin.php', true); 
		}
	} else {
		if ($validate != 'login') {
			// patikrinam ar administratorius prisijunges
			if (!is_logged('user')) redirect('', true); 
		}
	}	
	
	load_module('validate_' . $validate, 'validation', $validation_path);
}

function load_source() {
  foreach (func_get_args() as $src_name) {
    $src_file = SYS_SYSTEM. '/source/'. $src_name .'.src.php';
    if (file_exists($src_file)) 
      require_once($src_file);
		else 
			die('<b>'. $src_name. '.src.php</b> not found');
  }
}
?>