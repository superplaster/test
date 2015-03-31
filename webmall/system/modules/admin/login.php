<div id='align_center'>
	<div id='align_center_inside'>
	
	<form action='<?php echo $config['home_url']; ?>/admin.php?validate=login' method='post'>
		<table>
			<tr>
				<td style='text-align: left;'>Username <br /> <input type='text' name='username' class='text' /></td>
			</tr>
			
			<tr>
				<td style='text-align: left;'>Password <br /> <input type='password' name='password' class='text' /> </td>
			</tr>
			
			<tr>
				<td><input type='submit' name='login' class='button green' style='margin-left: 80px;' value='Login' /> </td>
			</tr>
		</table>
	</form>
	
	</div>
	<div id='align_center'>
		<div id='align_center_inside'>
			<?php echo get_msg('login_msg', 'width: 300px; margin-top: 5px;'); ?>
		</div>	
	</div>
</div>