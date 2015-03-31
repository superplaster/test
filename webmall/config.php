<?php

//MSSQL login details game side
$mssql_config = array(
	'server' => '127.0.0.1',
	'user' => 'Rage',
	'password' => 'Master12gamer69'
);
// Mysql database login details
$mysql_config = array(
	'host' => 'localhost',
	'user' => 'root',
	'pass' => 'Master12gamer',
	'db_name' => 'test'
);
// Admin Login and password to login admin panel 
$admin_config = array(
	'username' => 'bigben',
	'password' => 'bigben'
);
//full link to your script location (other setting dont change)
$config = array(
	'home_url' => 'http://101.100.138.68:8080/webmall/',
	'random_code' => 'type an random code to prevent sql injection',
	'max_slots' => 20,
);
// item section just add (, 'Category',) same DELETE
$items_cats = array(1 => 'Weapons', 'Armors','Pack', 'Lapis', 'Random' , 'Mounts' , 'New Items' ,'New Weapons','Armors Lv 85', );

?>