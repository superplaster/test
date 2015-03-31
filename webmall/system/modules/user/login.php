<div id='align_center'>
	<div id='align_center_inside' style='margin-top: 20px;'>
	
	<form action='<?php echo $config['home_url']; ?>/index.php?validate=login' method='post' onsubmit="this.js_enabled.value=1; return true;">
		<input type='hidden' name='js_enabled' value='0' />
		<input type='hidden' name='back_url' value='<?php echo back_url(); ?>' />	
		<table>
			<tr>
				<td style='text-align: left;'>Username <br /> <input type='text' name='username' class='text' /></td>
			</tr>
			
			<tr>
				<td style='text-align: left;'>Password <br /> <input type='password' name='password' class='text' /> </td>
			</tr>
			
			<tr>
				<td><input type='submit' name='login' class='button green' style='margin-left: 108px;' onclick='return showLoader();' value='Login' /> </td>
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