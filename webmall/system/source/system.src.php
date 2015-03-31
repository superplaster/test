<?php
function load_module($module, $mod_zone = '', $zone = 'admin') {
	global $config, $user_is_logged, $admin_is_logged, $user_data, $admin_config, $db, $items_cats;
	
	if (!empty($mod_zone)) {
		if (file_exists(SYS_SYSTEM . "/modules/" . $zone . "/" . $mod_zone))
			$path = SYS_SYSTEM . "/modules/" . $zone . "/" . $mod_zone . "/" . $module . ".php";
		else
			$path = SYS_SYSTEM . "/modules/" . $zone . "/" . $module . ".php";
	} else {
		$path = SYS_SYSTEM . "/modules/" . $zone . "/" . $module . ".php";
	}

	if (file_exists($path)) require($path);
}

function redirect($location, $home = false)  {
	global $config;
	
	$location = str_replace("&amp;", "&", $location);
	
	if ($home) $location = $config['home_url'] . '/' . $location;;
		
	header("Location: {$location}");
	exit;
}

function generate_random_code($length = 7, $level = 3) {
	list($usec, $sec) = explode(' ', microtime());
	srand((float) $sec + ((float) $usec * 100000));

	$chars[1] = "123456789";
	$chars[2] = "abcdefghijklmnopqrstuvwxyz";
	$chars[3] = "0123456789abcdefghijklmnopqrstuvwxyz";
	$chars[4] = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$chars[5] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";

	$code  = "";
	$counter   = 0;

	while ($counter < $length) {
		$actChar = substr($chars[$level], rand(0, strlen($chars[$level])-1), 1);

		if (!strstr($code, $actChar)) {
			$code .= $actChar;
			$counter++;
		}
	}
	
	return $code;
}

function set_msg($msg, $type = 'warning', $redirect = false, $msg_id = '') {
	if ($redirect) {
		write_session($msg_id, "<div class='message {$type}'><p>{$msg}</p></div>");
		redirect($redirect);
	} else {
		exit("{$msg_id}<div class='message {$type}'><p>{$msg}</p></div>");
	}
}

function get_msg($msg_id, $style = '') {
	$style_start = '';
	$style_end = '';
	
	if ($style) {
		$style_start = "<div style='{$style}'>";
		$style_end = "</div>";
	}
	
	if (read_session($msg_id)) return $style_start . read_session($msg_id) . remove_session($msg_id) . $style_end;
	
	return false;
}

function current_url() {
	$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? clean_url($_SERVER['REQUEST_URI']) : "";
		
	$url = 'http://';
	$url .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"] : $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	
	return $url;
}
	
function _basename($file = false) {
  if ($file == false || strlen($file) <= 0) return false;
   
  $file = explode('?', $file);
  $file = explode('/', $file[0]);
  $basename = $file[count($file) - 1];

  return $basename;   
}

function get_url_param($param) {
	if (isset($_GET[$param])) return clean_url($_GET[$param]);
	
	return false;
}

function shorten_string($string, $length=NULL) {
  if ($length == NULL)
		$length = 50;
   
  $stringDisplay = substr(strip_tags($string), 0, $length);
	if (strlen(strip_tags($string)) > $length) 
		$stringDisplay .= ' ...';
	
  return $stringDisplay;
}

function more_items($number, $i_name, $i_id, $i_count, $i_ali) {
	$des = array('Off', 'On');
	
	$number = $number + 1;
	
	$result = '';
	
	for($i=2;$i<=$number;$i++) {
		$item_name = (isset($i_name[$i])) ? $i_name[$i] : read_session('item_name_' . $i);
		$item_id = (isset($i_id[$i])) ? $i_id[$i] : read_session('item_id_' . $i);
		$item_count = (isset($i_count[$i])) ? $i_count[$i] : read_session('item_count_' . $i);
	
		$result .= "<tr>
			<td style='width: 100px;'>Item name <br /> <input type='text' name='item_name_{$i}' class='text' value='{$item_name}' /></td>
			<td style='width: 100px;'>Item id <br /> <input type='text' name='item_id_{$i}' class='text' value='{$item_id}' /></td>
			<td style='width: 100px;'>Item count <br /> <input type='text' name='item_count_{$i}' class='text' value='{$item_count}' /></td>
			<td style='width: 50px;'>ALI <br /> <select name='item_ali_{$i}' class='select'>";
		
			foreach ($des as $key => $value) {
				if (isset($data)) { 
					if ($key == $i_ali[$i]) $selected = "selected='selected'"; else $selected = ""; 
				} else { 
					if (read_session('item_ali_' . $i) && $key == read_session('item_ali_' . $i)) $selected = "selected='selected'"; else $selected = ""; 
				}
			
				$result .= "<option value='{$key}' {$selected}>{$value}</option>"; 
			}
		
		$result .= "</select></td>
		</tr>";
	}

	return $result;
}

?>