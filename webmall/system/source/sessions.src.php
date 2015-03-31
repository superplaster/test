<?php
function write_session($key, $value) {
  $_SESSION[$key] = $value;
}

function read_session($key) {
  if (isset($_SESSION[$key])) 
    return $_SESSION[$key];
	else 
		return false;
}
  
function remove_session($key) {
  if (isset($_SESSION[$key])) unset($_SESSION[$key]);
}    

function ead_session($key) {
	if (isset($_SESSION[$key])) {
  	echo $_SESSION[$key];
		unset($_SESSION[$key]);
  } else {
		return false;
	}
}

?>