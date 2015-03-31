<?php require_once('core.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Donate admin</title>	

	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /> 
	<meta name='description' content='' />
	<meta name='keywords' content='' /> 
	<meta name='author' content='' />
	<link href="<?php echo $config['home_url']; ?>/user/admin/css/reset.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/admin/css/general.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/admin/css/borders.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/admin/css/inputs.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/admin/css/messages.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/admin/css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/admin/css/buttons.css" rel="stylesheet" type="text/css" />
	
	<script type='text/javascript' src='<?php echo $config['home_url']; ?>/user/admin/js/jquery.js'></script>
</head>
<body>
	<div id='page_outer'>
		<div id='page_inner'>
			<div id='page_box'>
			
				<?php
					if ($admin_is_logged) {
				?>
					<div id='menu'>
						<a href='<?php echo $config['home_url']; ?>/admin.php?do=shop_items'>Shop items</a> | <a href='<?php echo $config['home_url']; ?>/admin.php?validate=logout'>Logout</a>
					</div>
				<?php
						load_module((get_url_param('do')) ? get_url_param('do') : 'main', '', 'admin');
					} else {
						load_module('login', '', 'admin');
					}
				?>
				
			</div>
		</div>
	</div>
</body>
</html>