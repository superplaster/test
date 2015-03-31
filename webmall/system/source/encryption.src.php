<?php
function password_encode($password, $random_code = '')	 {
	$password = sha1(md5(md5($password.$random_code).sha1(md5($random_code)).md5($password)));
	return $password;
}

function simple_encrypt($data) {
	global $config;
	
	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($config['random_code']), $data, MCRYPT_MODE_CBC, md5(md5($config['random_code']))));
}

function simple_decrypt($data) {
	global $config;
	
	return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($config['random_code']), base64_decode($data), MCRYPT_MODE_CBC, md5(md5($config['random_code']))), "\0");
}
?>