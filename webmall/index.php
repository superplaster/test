<?php require_once('core.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	

	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /> 
	<meta name='description' content='' />
	<meta name='keywords' content='' /> 
	<meta name='author' content='' />

	<link href="<?php echo $config['home_url']; ?>/user/css/reset.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/css/general.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/css/borders.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/css/inputs.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/css/messages.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['home_url']; ?>/user/css/buttons.css" rel="stylesheet" type="text/css" />
	
	<script type='text/javascript' src='<?php echo $config['home_url']; ?>/user/js/jquery.js'></script>
	<script type='text/javascript' src='<?php echo $config['home_url']; ?>/user/js/overlay.js'></script>
	<script type='text/javascript' src='<?php echo $config['home_url']; ?>/user/js/script.js'></script>
</head>
<body>


	<div id='page_outer'>
               <font style="font-size:90px" color="#FFFFFF" face="Nightmare Before Christmas">Rage Web Mall</font> <br>

		<div id='page_inner'>
			<div id='page_box'>
			
			
				<?php
					if ($user_is_logged) {
					       
                                            
                                               echo "<fieldset id='cats'>";
					
                                               echo "<legend><br>Categories</legend>";
						$i = 1;
						$count = count($items_cats);
						foreach ($items_cats as $key => $value) {
							if (get_url_param('cat') == $key) $active_cat = "class='active_cat'"; else $active_cat = "";
						
							echo "<a href='{$config['home_url']}/index.php?cat={$key}' {$active_cat}>{$value}</a>";
							if ($count != $i) echo " | ";
							
							$i++;
						}
					echo "</fieldset>";
				?>
					<div id='menu'>
						Hello <b><?php echo read_session('username'); ?>!</b> You have <b><?php echo $user_data['Point']; ?></b> <?php echo ($user_data['Point'] > 1) ? 'points' : 'point'; ?> <div style='float: right;'><a href='<?php echo $config['home_url']; ?>/index.php?validate=logout'>Logout</a></div>
					</div>
				<?php
						load_module((get_url_param('do')) ? get_url_param('do') : 'main', '', 'user');
					} else {
						load_module('login', '', 'user');
					}
				?>
				
		  </div>
		</div>

	</div>
    

</body>
</html>