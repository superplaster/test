<?php
	
	$ex_item_name = '';
	$ex_item_id = '';
	$ex_item_count = '';
	$ex_item_ali = '';
	
	if (get_url_param('edit')) {
		$query = mysql_query("SELECT * FROM donate_items WHERE id = '" . get_url_param('edit') . "'");
		$data = mysql_fetch_array($query);
	}
	
	$_page = (get_url_param('page')) ? '&page=' . get_url_param('page') : '';
	
	$current_url = base64_decode(back_url());
	
	if (get_url_param('more_item')) {
		$new_more = get_url_param('more_item') + 1;
		$new_more_minus = (get_url_param('more_item') > 0) ? get_url_param('more_item') - 1 : get_url_param('more_item');
		$_more_item_url = str_replace('more_item=' . get_url_param('more_item'), 'more_item=' . $new_more, $current_url);
		$_more_item_url_remove = str_replace('more_item=' . get_url_param('more_item'), 'more_item=' . $new_more_minus, $current_url);
	} else {
		$_more_item_url = $current_url . '&more_item=1';
		$_more_item_url_remove = $current_url;
	}
?>
<fieldset class='add_item_box'>
	<?php
		$add_type = array('one' => 'Add item', 'pack' => 'Add item pack');
		$i = 0;
		$count = count($add_type);
		foreach ($add_type as $key => $val) {
			if (get_url_param('action')) { if (get_url_param('action') == $key) $selected = "selected"; else $selected = ""; } else $selected = "";
			
			if ($i == 0 && !get_url_param('action'))
				echo "<div class='add_item_box_title selected_first'><a href='{$config['home_url']}/admin.php?do=shop_items&action={$key}{$_page}'>{$val}</a></div>";
			else
				echo "<div class='add_item_box_title {$selected}'><a href='{$config['home_url']}/admin.php?do=shop_items&action={$key}{$_page}'>{$val}</a></div>";
			
			$i++;
		}
	?>
	<div class='clear'></div>

	<?php 
		// not pack
		if (get_url_param('action') == 'one' || !get_url_param('action')) { 
	?>
	<form action='<?php echo $config['home_url']; ?>/admin.php?validate=item' method='post'>
		<input type='hidden' name='back_path' value='<?php echo back_url(); ?>' />
		
		<?php
			if (!get_url_param('edit')) {
				echo "<input type='hidden' name='add_item' value='1' />";
			} else {
				echo "<input type='hidden' name='edit_item' value='1' />";
				echo "<input type='hidden' name='edit_id' value='{$data['id']}' />";
			}
		?>
		
		<input type='hidden' name='type' value='one' />
		
		<table style='margin-left: 10px; margin-top: 10px; width: 90%;'>
			<tr>
				<td style='width: 100px;'>Item name <input type='text' name='item_name' class='text' value='<?php if (isset($data['item_name'])) echo $data['item_name']; else echo read_session('item_name'); ?>' /></td>
				<td style='width: 100px;'>Item id <input type='text' name='item_id' class='text' value='<?php if (isset($data['item_id'])) echo $data['item_id']; else echo read_session('item_id'); ?>' /></td>
				<td style='width: 100px;'>Item price <input type='text' name='item_price' class='text' value='<?php if (isset($data['item_price'])) echo $data['item_price']; else echo read_session('item_price'); ?>' /></td>
				<td style='width: 100px;'>Item count <input type='text' name='item_count' class='text' value='<?php if (isset($data['item_count'])) echo $data['item_count']; else echo read_session('item_count'); ?>' /></td>
				<td style='width: 60px;'>ALI <select name='item_ali' class='select'>
					<?php
						$des = array('Off', 'On');
						foreach ($des as $key => $value) {
							if (isset($data)) { 
								if ($key == $data['item_ali']) $selected = "selected='selected'"; else $selected = ""; 
							} else { 
								if (read_session('item_ali') && $key == read_session('item_ali')) $selected = "selected='selected'"; else $selected = ""; 
							}
							
							echo "<option value='{$key}' {$selected}>{$value}</option>"; 
						}
					?>
				</select></td>
			</tr>
			
			<tr>
				<td>Category <br /> <select name='item_cat' class='select' style='width: 158px;'>
						<?php
							foreach ($items_cats as $key => $value) {
								if (isset($data)) { 
									if ($key == $data['item_cat']) $selected = "selected='selected'"; else $selected = ""; 
								} else { 
									if (read_session('item_cat') && $key == read_session('item_cat')) $selected = "selected='selected'"; else $selected = ""; 
								}
							
								echo "<option value='{$key}' {$selected}>{$value}</option>";
							}
						?>
					</select>
				</td>
				
				<td>Image <br /> <input type='text' name='item_image' class='text' value='<?php if (isset($data['item_image'])) echo $data['item_image']; else echo read_session('item_image'); ?>'/>
				</td>
			</tr>
		</table>
		
		<div class='item_submit_button'>
			<input type='submit' name='item' class='button green' value='<?php echo (isset($data)) ? 'Update item' : 'Add item'; ?>' />
		</div>
	</form>
	
	<?php echo get_msg('item_msg', 'width: 720px; margin-top: 10px; margin-bottom: -5px; margin-left: 10px;'); ?>
	<?php } 
	// pack
	else { ?>
	<form action='<?php echo $config['home_url']; ?>/admin.php?validate=item' method='post'>
		<input type='hidden' name='back_path' value='<?php echo back_url(); ?>' />
		
		<?php
			if (!get_url_param('edit')) {
				echo "<input type='hidden' name='add_item' value='1' />";
			} else {
				echo "<input type='hidden' name='edit_item' value='1' />";
				echo "<input type='hidden' name='edit_id' value='{$data['id']}' />";
				
				$ex_item_name = explode(',', $data['item_name']);
				$ex_item_id = explode(',', $data['item_id']);
				$ex_item_count = explode(',', $data['item_count']);
				$ex_item_ali = explode(',', $data['item_ali']);
			}
		?>
		
		<input type='hidden' name='type' value='pack' />
		<input type='hidden' name='items_count' value='<?php echo get_url_param('more_item') + 2; ?>' />
		
		<table style='margin-left: 10px; margin-top: 10px; width: 70%;'>
			<tr>
				<td style='width: 100px;'>Item name <br /> <input type='text' name='item_name_1' class='text' value='<?php if (isset($ex_item_name[0])) echo $ex_item_name[0]; else echo read_session('item_name_1'); ?>' /></td>
				<td style='width: 100px;'>Item id <br /> <input type='text' name='item_id_1' class='text' value='<?php if (isset($ex_item_id[0])) echo $ex_item_id[0]; else echo read_session('item_id_1'); ?>' /></td>
				<td style='width: 100px;'>Item count <br /> <input type='text' name='item_count_1' class='text' value='<?php if (isset($ex_item_count[0])) echo $ex_item_count[0]; else echo read_session('item_count_1'); ?>' /></td>
				<td style='width: 50px;'>ALI <br /> <select name='item_ali_1' class='select'>
					<?php
						$des = array('Off', 'On');
						foreach ($des as $key => $value) {
							if (isset($ex_item_ali[0])) { 
								if ($key == $ex_item_ali[0]) $selected = "selected='selected'"; else $selected = ""; 
							} else { 
								if (read_session('item_ali_1') && $key == read_session('item_ali_1')) $selected = "selected='selected'"; else $selected = ""; 
							}
							
							echo "<option value='{$key}' {$selected}>{$value}</option>"; 
						}
					?>
				</select></td>
			</tr>
			
			<tr>
				<td style='width: 100px;'>Item name <br /> <input type='text' name='item_name_2' class='text' value='<?php if (isset($ex_item_name[1])) echo $ex_item_name[1]; else echo read_session('item_name_2'); ?>' /></td>
				<td style='width: 100px;'>Item id <br /> <input type='text' name='item_id_2' class='text' value='<?php if (isset($ex_item_id[1])) echo $ex_item_id[1]; else echo read_session('item_id_2'); ?>' /></td>
				<td style='width: 100px;'>Item count <br /> <input type='text' name='item_count_2' class='text' value='<?php if (isset($ex_item_count[1])) echo $ex_item_count[1]; else echo read_session('item_count_2'); ?>' /></td>
				<td style='width: 50px;'>ALI <br /> <select name='item_ali_2' class='select'>
					<?php
						$des = array('Off', 'On');
						foreach ($des as $key => $value) {
							if (isset($ex_item_ali[1])) { 
								if ($key == $ex_item_ali[1]) $selected = "selected='selected'"; else $selected = ""; 
							} else { 
								if (read_session('item_ali_2') && $key == read_session('item_ali_2')) $selected = "selected='selected'"; else $selected = ""; 
							}
							
							echo "<option value='{$key}' {$selected}>{$value}</option>"; 
						}
					?>
				</select></td>
			</tr>
			
			<?php if (get_url_param('more_item')) echo more_items(get_url_param('more_item'), $ex_item_name, $ex_item_id, $ex_item_count, $ex_item_ali); ?>
			
			<tr>
				<td>Pack category <br /> <select name='item_cat' class='select' style='width: 158px;'>
						<?php
							foreach ($items_cats as $key => $value) {
								if (isset($data)) { 
									if ($key == $data['item_cat']) $selected = "selected='selected'"; else $selected = ""; 
								} else { 
									if (read_session('item_cat') && $key == read_session('item_cat')) $selected = "selected='selected'"; else $selected = ""; 
								}
							
								echo "<option value='{$key}' {$selected}>{$value}</option>";
							}
						?>
					</select>
				</td>
				
				<td>Pack image <br /> <input type='text' name='item_image' class='text' value='<?php if (isset($data['item_image'])) echo $data['item_image']; else echo read_session('item_image'); ?>'/>
				</td>
				
				<td>Pack price <br /> <input type='text' name='item_price' class='text' value='<?php if (isset($data['item_price'])) echo $data['item_price']; else echo read_session('item_price'); ?>'/>
				</td>
			</tr>
		</table>
		
		<div class='item_submit_button'>
			<input type='submit' name='item' class='button green' value='<?php echo (isset($data)) ? 'Update item pack' : 'Add item pack'; ?>' />
			<input type='button' name='item' class='button green' onclick="location.href='<?php echo $_more_item_url; ?>'" value='Add 1' />
			<input type='button' name='item' class='button green' onclick="location.href='<?php echo $_more_item_url_remove; ?>'" value='Remove 1' />
		</div>
	</form>
	
	<?php echo get_msg('item_msg', 'width: 570px; margin-top: 10px; margin-bottom: -5px; margin-left: 10px;'); ?>
	<?php } ?>
</fieldset>

<div class='added_items_box'>
	<?php
	
		$query = mysql_query("SELECT * FROM donate_items");
		$Num_Rows = mysql_num_rows($query);

		$page = (isset($_GET['page'])) ? $_GET['page'] : '';

		$Per_Page = 10;

		$pagination = new Pagination();
		$pagination->setLink("admin.php?do=shop_items&page=%s");
		$pagination->setPage($page);
		$pagination->setSize($Per_Page);
		$pagination->setTotalRecords($Num_Rows);
		
		$query = mysql_query("SELECT * FROM donate_items " . $pagination->getLimitSql()) or die(mysql_error());
		
		if ($Num_Rows > 0) {
	
	?>
	<table class='added_items_table' style='width: 100%; text-align: center;'>
		<tr>
			<td style='font-weight: bold;'>Name</td>
			<td style='font-weight: bold;'>ID</td>
			<td style='font-weight: bold;'>Price</td>
			<td style='font-weight: bold;'>Count</td>
			<td style='font-weight: bold;'>Category</td>
			<td style='font-weight: bold;'>Type</td>
			<td style='font-weight: bold;'>Image name</td>
			<td style='font-weight: bold;'>#</td>
			<td style='font-weight: bold;'>#</td>
		</tr>
		
		<?php  
			$row = array();
				while($row = mysql_fetch_array($query)) {
				
					$ali_status = ($row['item_ali'] == 1) ? "On" : "Off";
					$action = ($row['item_pack'] != 0) ? "pack" : "one";
					$image = ($row['item_image']) ? $row['item_image'] : "no image";
					
					if ($row['item_pack'] != 0) {
						$ex_item_name = explode(',', $row['item_name']);
						$pack_count = count($ex_item_name) - 3;
						if ($pack_count == 1 || $pack_count == 0) {
							$pack_count = 0;
						}
						
						echo "<tr class='tr_hover'>
							<td><a href='#' title='{$row['item_name']}'>Informacija</a></td>
							<td><a href='#' title='{$row['item_id']}'>Informacija</a></td>
							<td>{$row['item_price']}</td>
							<td><a href='#' title='{$row['item_count']}'>Informacija</a></td>
							<td>{$items_cats[$row['item_cat']]}</td>
							<td>{$action}</td>
							<td>{$image}</td>
							<td><a href='{$config['home_url']}/admin.php?do=shop_items{$_page}&edit={$row['id']}&action={$action}&more_item={$pack_count}'>Edit</a></td>
							<td><a href='{$config['home_url']}/admin.php?do=delete&id={$row['id']}&back=" . back_url() . "' style='color: red;'>Delete</a></td>
						</tr>";
					} else {
						echo "<tr class='tr_hover'>
							<td>{$row['item_name']}</td>
							<td>{$row['item_id']}</td>
							<td>{$row['item_price']}</td>
							<td>{$row['item_count']}</td>
							<td>{$items_cats[$row['item_cat']]}</td>
							<td>{$action}</td>
							<td>{$image}</td>
							<td><a href='{$config['home_url']}/admin.php?do=shop_items{$_page}&edit={$row['id']}&action={$action}'>Edit</a></td>
							<td><a href='{$config['home_url']}/admin.php?do=delete&id={$row['id']}&back=" . back_url() . "' style='color: red;'>Delete</a></td>
						</tr>";
					}
				}
				
			echo "</table>";
			} else {
				echo "No items";
			}
			
			$pagination = $pagination->create_links();
			
			if (!empty($pagination)) {
				echo $pagination;
			}
		?>
		<div class='clear'>
</div>