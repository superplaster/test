<?php
function is_logged($type = 'user') {	
	global $config;
	
	if ($type == 'user') {
		$userHA = md5(md5($_SERVER['HTTP_USER_AGENT']).md5($config['home_url']).md5($config['random_code']).read_session('username'));
		if (read_session('username') && read_session('userHA') == $userHA) return true;
	}	
	
	if ($type == 'admin') {
		$adminHA = md5(md5($_SERVER['HTTP_USER_AGENT']).md5($config['home_url']).md5($config['random_code']).read_session('admin'));
		if (read_session('admin') && read_session('adminHA') == $adminHA) return true;
	}
	
	return false;
}

function clean_url($url)  {
	$bad_entities = array("&", "\"", "'", '\"', "\'", "<", ">", "(", ")", "*");
	$safe_entities = array("&amp;", "", "", "", "", "", "", "", "", "");
	$url = str_replace($bad_entities, $safe_entities, $url);
	return $url;
}

function safe_get($check_url)  {
	$return = false;
	
	if (is_array($check_url)) {
		foreach ($check_url as $value) {
			if (safe_get($value) == true) return true;
		}
	} else {
		$check_url = str_replace(array("\"", "\'"), array("", ""), urldecode($check_url));
		if (preg_match("/<[^<>]+>/i", $check_url)) return true;
	}
	
	return $return;
}

function mysql_escape_mimic($inp) { 
	if(is_array($inp)) return array_map(__METHOD__, $inp); 

	if(!empty($inp) && is_string($inp))
		return str_replace(array('\\', "\0", "\n", "\r", "'", '"', ""), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 

	return $inp; 
}

function _is_int($number, $type='int') {
	if (!is_numeric($number))
		return false;
	
	if ($type == 'int') {
		if ((int)$number != $number) return false;
	}
	
	if ($type == 'float') {
		if ((float)$number != $number) return false;
	}
	
	if ($type == 'int&float') {
		if ((int)$number != $number && (float)$number != $number) return false;
	}
	
	return true;
}



?>			