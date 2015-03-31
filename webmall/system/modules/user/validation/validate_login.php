<?php
if (isset($_POST['login'])) {
	$post_data = do_request($_POST, true);
	$back_url = str_replace('&amp;', '&', base64_decode($_POST['back_url']));
	
	if ($_POST['js_enabled'] != 1)
		set_msg('You must have javascript enabled in yout browser', 'error', $back_url, 'login_msg');
		
	
	if (!$post_data)
		set_msg('Please fill all fields', 'error', $back_url, 'login_msg');
		
	$query = mssql_query("SELECT RowID FROM PS_UserData.dbo.Users_Master WHERE userID = '{$post_data['username']}' AND Pw = '{$post_data['password']}'");
	if (mssql_num_rows($query) <= 0)
		set_msg('Bad username and/or password', 'error', $back_url, 'login_msg');
		
	session_regenerate_id();
	write_session('username', $post_data['username']);
	write_session('userHA', md5(md5($_SERVER['HTTP_USER_AGENT']).md5($config['home_url']).md5($config['random_code']).$post_data['username']));
	redirect('index.php?cat=1', true);
}
?>