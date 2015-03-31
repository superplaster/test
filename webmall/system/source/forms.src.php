<?php
function do_request($request_data, $check_empty, $check_elements = array(), $not_check_elements = array()) {
	$post_data = array();

	// nieko netikrinam
	if (!$check_empty) {
		foreach ($request_data as $key => $data) $post_data[$key] = mysql_escape_mimic($data);
			
		return $post_data;
	}
	
	// tikrinam visus elementus
	// jei nors vienas elementas tuscias - stabdom viska
	if ($check_empty && empty($check_elements) && empty($not_check_elements)) {
		foreach ($request_data as $key => $data)
			if (!empty($data) || (_is_int($data) && $data == 0)) $post_data[$key] = mysql_escape_mimic($data); else return false;
			
		return $post_data;
	}
	
	// tikrinam tuos elementus kuriu reikia
	// jei nors vienas elementas (is tu kuriuos reikia patikrinti) tuscias - stabdom viska
	if ($check_empty && !empty($check_elements) && empty($not_check_elements)) {
		foreach ($request_data as $key => $data) {
			if (in_array($key, $check_elements)) {
				if (!empty($data)) $post_data[$key] = mysql_escape_mimic($data); else return false;
			} else {
				$post_data[$key] = mysql_escape_mimic($data);
			}
		}
			
		return $post_data;
	}
	
	// tikrinam visus elementus iskyrus tu kuriu nereikia
	// jei nors vienas elementas (is tu kuriuos reikia patikrinti) tuscias - stabdom viska
	if ($check_empty && empty($check_elements) && !empty($not_check_elements)) {
		foreach ($request_data as $key => $data) {
			if (in_array($key, $not_check_elements)) {
				$post_data[$key] = mysql_escape_mimic($data);
			} else {
				if (!empty($data)) $post_data[$key] = mysql_escape_mimic($data); else return false;
			}
		}
	
		return $post_data;
	}
	
	return false;
}

function save_input_values($data) {
	if (!empty($data)) {
		foreach ($data as $key => $value) write_session($key, $value);
	}
}

function remove_input_values($data) {
	if (!empty($data)) {
		foreach ($data as $key => $value) remove_session($key);
	}
}

function back_url() {
	return base64_encode(current_url());
}

function _empty($data) {
	if (is_array($data)) {
		foreach ($data as $value) {
			if (empty($value)) return true;
		}
	} else {
		if (empty($data)) return true;
	}
	
	return false;
}

?>