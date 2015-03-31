<?php
if (get_url_param('do') == 'delete' && get_url_param('id') && get_url_param('back')) {
	$id = get_url_param('id');
	$back_path = str_replace('&amp;', '&', base64_decode(get_url_param('back')));
	
	$query = mysql_query("SELECT * FROM donate_items WHERE id = '{$id}'");
	if (mysql_num_rows($query) > 0) {
		mysql_query("DELETE FROM donate_items WHERE id = '{$id}'");
	}

	redirect($back_path);
}

?>