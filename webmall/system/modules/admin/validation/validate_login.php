<?php
if (isset($_POST['login'])) {
	$post_data = do_request($_POST, true);
	
	if (!$post_data)
		set_msg('Please fill all fields', 'error', $config['home_url'] . '/admin.php', 'login_msg');
		
	if ($post_data['username'] != $admin_config['username'] || $post_data['password'] != $admin_config['password'])
		set_msg('Bad username and/or password', 'error', $config['home_url'] . '/admin.php', 'login_msg');
 		
	session_regenerate_id();
	write_session('admin', '1');
	write_session('adminHA', md5(md5($_SERVER['HTTP_USER_AGENT']).md5($config['home_url']).md5($config['random_code']).'1'));
	redirect('admin.php', true);
}
?>