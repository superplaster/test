<?php
if ($admin_is_logged) {
	remove_session('adminHA');
	remove_session('admin');
	$admin_is_logged = false;
}

redirect('admin.php', true);

?>	