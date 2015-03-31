<?php
function get_file_extension($str) {
  $i = strrpos($str,".");
  if (!$i) { return ""; } 
  $l = strlen($str) - $i;
  $ext = substr($str,$i+1,$l);
	return $ext;
}

function _format_bytes($a_bytes) {
  if ($a_bytes < 1024) {
    return $a_bytes .' B';
	} elseif ($a_bytes < 1048576) {
    return round($a_bytes / 1024, 2) .' KB';
  } elseif ($a_bytes < 1073741824) {
    return round($a_bytes / 1048576, 2) . ' MB';
  } elseif ($a_bytes < 1099511627776) {
    return round($a_bytes / 1073741824, 2) . ' GB';
  } elseif ($a_bytes < 1125899906842624) {
    return round($a_bytes / 1099511627776, 2) .' TB';
  } elseif ($a_bytes < 1152921504606846976) {
    return round($a_bytes / 1125899906842624, 2) .' PB';
  } elseif ($a_bytes < 1180591620717411303424) {
    return round($a_bytes / 1152921504606846976, 2) .' EB';
  } elseif ($a_bytes < 1208925819614629174706176) {
    return round($a_bytes / 1180591620717411303424, 2) .' ZB';
  } else {
    return round($a_bytes / 1208925819614629174706176, 2) .' YB';
  }
} 

function safe_file_name($filename) {
	$find = array("ą","č","ę","ė","į","š","ų","ū","ž","Ą","Č","Ę","Ė","Į","Š","Ų","Ū","Ž",' ', '(', ')', '[', ']', '<', '>', '-', '\'', '"', '+');
	$replace = array("a","c","e","e","i","s","u","u","z","A","C","E","E","I","S","U","U","Z",'_', '', '', '', '', '', '', '', '', '', '');
	$safe_filename = str_replace($find, $replace, $filename);
	
	return $safe_filename;
}

function output_file($file, $name, $mime_type = '') {
	global $db;
	
	if (!is_readable($file)) die('File not found or inaccessible!');
	 
	$size = filesize($file);
	$name = rawurldecode($name);
	 
	$known_mime_types=array(
		"pdf" => "application/pdf",
		"txt" => "text/plain",
		"html" => "text/html",
		"htm" => "text/html",
		"exe" => "application/octet-stream",
		"zip" => "application/zip",
		"doc" => "application/msword",
		"xls" => "application/vnd.ms-excel",
		"ppt" => "application/vnd.ms-powerpoint",
		"gif" => "image/gif",
		"png" => "image/png",
		"jpeg"=> "image/jpg",
		"jpg" =>  "image/jpg",
		"php" => "text/plain"
	);
	 
	if ($mime_type == '') {
		$file_extension = strtolower(substr(strrchr($file,"."),1));
		
		if (array_key_exists($file_extension, $known_mime_types))
			$mime_type=$known_mime_types[$file_extension];
		else 
			$mime_type="application/force-download";
	}
	 
	 @ob_end_clean();
	 
	if (ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off');
	 
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . $name . '"');
	header("Content-Transfer-Encoding: binary");
	header('Accept-Ranges: bytes');
	 
	header("Cache-control: private");
	header('Pragma: private');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	 
	if (isset($_SERVER['HTTP_RANGE'])) {
		list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
		list($range) = explode(",", $range,2);
		list($range, $range_end) = explode("-", $range);
		$range = intval($range);
		
		if (!$range_end)
			$range_end = $size-1;
		else
			$range_end = intval($range_end);
	 
		$new_length = $range_end - $range + 1;
		header("HTTP/1.1 206 Partial Content");
		header("Content-Length: {$new_length}");
		header("Content-Range: bytes $range-$range_end/$size");
	} else {
		$new_length = $size;
		header("Content-Length: " . $size);
	}
	 
	$chunksize = 1 * (1024 * 1024); 
	$bytes_send = 0;
	if ($file = fopen($file, 'r')) {
		if (isset($_SERVER['HTTP_RANGE']))
			fseek($file, $range);
	 
		while(!feof($file) && (!connection_aborted()) && ($bytes_send < $new_length)) {
			$buffer = fread($file, $chunksize);
			print($buffer);
			flush();
			$bytes_send += strlen($buffer);
		}
		fclose($file);
		
	} else {
		die('Error - can not open file.');
	}

	die();
}	
?>