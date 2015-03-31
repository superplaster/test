<?php
if (isset($_POST['buy'])) {
	$post_data = do_request($_POST, true);
	$back_url = str_replace('&amp;', '&', base64_decode($_POST['back_url']));
	
	$left_empty_slots = 0;
	
	if ($_POST['js_enabled'] != 1)
		set_msg('You must have javascript enabled in your browser', 'error', $back_url, 'buy_msg_' . $post_data['id']);
	
	if (!$post_data)
		set_msg('Please fill all fields', 'error', $back_url, 'buy_msg_' . $post_data['id']);
		
	$query = mysql_query("SELECT * FROM donate_items WHERE id = '{$post_data['id']}'") or die();
	if (mysql_num_rows($query) <= 0)
		set_msg('Unexpected error!', 'error', $back_url, 'buy_msg_' . $post_data['id']);
		
	$item_data = mysql_fetch_array($query);
	
	if ($user_data['Point'] < $item_data['item_price'])
		set_msg("You do not have enough points", 'error', $back_url, 'buy_msg_' . $post_data['id']);
	
	if ($item_data['item_pack'] == 0) {
		if ($item_data['item_ali']) {
			$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}'") or die();
			$count_slots = mssql_num_rows($query);
			if ($config['max_slots'] > $count_slots)
				$left_empty_slots = $config['max_slots'] - $count_slots;
		
			if ($left_empty_slots >= $item_data['item_count']) {
				for($i=1;$i<=$item_data['item_count'];$i++) {
					$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}'") or die();
					$s = 0;
					$empty_slot = false;
					if (mssql_num_rows($query) > 0) {
						while ($row = mssql_fetch_array($query)) {
							$s++;
							
							if ($s <= $config['max_slots']) {
								$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}' AND slot = '{$s}'") or die();
								if (mssql_num_rows($query) <= 0) {
									$empty_slot = $s;
									break;
								}
							} else {
								$empty_slot = false;
								break;
							}
						}
						
						if ($empty_slot) {
							mssql_query("INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES ('{$user_data['UserUID']}','{$empty_slot}','{$item_data['item_id']}','1','" . date('Y-m-d H:i:s') . "')") or die();
						} else {
							set_msg('No empty slots for items', 'error', $back_url, 'buy_msg_' . $post_data['id']);
						}
					} else {
						$empty_slot = 1;
						mssql_query("INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES ('{$user_data['UserUID']}','{$empty_slot}','{$item_data['item_id']}','1','" . date('Y-m-d H:i:s') . "')") or die();
					}
				}
			} else {
				set_msg('No empty slots for items', 'error', $back_url, 'buy_msg_' . $post_data['id']);
			}
		} else {
			$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}'") or die();
			$s = 0;
			$empty_slot = false;
			if (mssql_num_rows($query) > 0) {
				while ($row = mssql_fetch_array($query)) {
					$s++;
					
					if ($s <= $config['max_slots']) {
						$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}' AND slot = '{$s}'") or die();
						if (mssql_num_rows($query) <= 0) {
							$empty_slot = $s;
							break;
						}
					} else {
						$empty_slot = false;
						break;
					}
				}

				if ($empty_slot) {
					mssql_query("INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES ('{$user_data['UserUID']}','{$empty_slot}','{$item_data['item_id']}','{$item_data['item_count']}','" . date('Y-m-d H:i:s') . "')") or die();
				} else {
					set_msg('No empty slots for items', 'error', $back_url, 'buy_msg_' . $post_data['id']);
				}
			} else {
				$empty_slot = 1;
				mssql_query("INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES ('{$user_data['UserUID']}','{$empty_slot}','{$item_data['item_id']}','{$item_data['item_count']}','" . date('Y-m-d H:i:s') . "')") or die();
			}
		}
		
		$new_points = $user_data['Point'] - $item_data['item_price'];
		mssql_query("UPDATE PS_UserData.dbo.Users_Master SET Point = '{$new_points}' WHERE UserUID = '{$user_data['UserUID']}'") or die();
		set_msg('Item purchased!', 'success', $back_url, 'buy_msg_' . $post_data['id']);
	} 
	// pack
	else {
		$ex_item_ali = explode(',', $item_data['item_ali']);
		$ex_item_name = explode(',', $item_data['item_name']);
		$ex_item_id = explode(',', $item_data['item_id']);
		$ex_item_count = explode(',', $item_data['item_count']);
		$count = count($ex_item_ali)-1;
		
		$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}'") or die();
		$count_slots = mssql_num_rows($query);
		if ($config['max_slots'] > $count_slots)
			$left_empty_slots = $config['max_slots'] - $count_slots;
		
		if ($left_empty_slots >= $count) {
			for ($e=0;$e<=$count;$e++) {
				if ($ex_item_ali[$e]) {
					for($i=1;$i<=$ex_item_count[$e];$i++) {
						$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}'") or die();
						$s = 0;
						$empty_slot = false;
						if (mssql_num_rows($query) > 0) {
							while ($row = mssql_fetch_array($query)) {
								$s++;

								if ($s <= $config['max_slots']) {
									$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}' AND slot = '{$s}'") or die();
									if (mssql_num_rows($query) <= 0) {
										$empty_slot = $s;
										break;
									}
								} else {
									$empty_slot = false;
									break;
								}
							}
							
							if ($empty_slot) {
								mssql_query("INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES ('{$user_data['UserUID']}','{$empty_slot}','{$ex_item_id[$e]}','1','" . date('Y-m-d H:i:s') . "')") or die();
							} else {
								set_msg('No empty slots for items', 'error', $back_url, 'buy_msg_' . $post_data['id']);
							}
						} else {
							$empty_slot = 1;
							mssql_query("INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES ('{$user_data['UserUID']}','{$empty_slot}','{$ex_item_id[$e]}','1','" . date('Y-m-d H:i:s') . "')") or die();
						}
					}
				} else {
					$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}'") or die();
					$s = 0;
					$empty_slot = false;
					if (mssql_num_rows($query) > 0) {
						while ($row = mssql_fetch_array($query)) {
							$s++;
							
							if ($s <= $config['max_slots']) {
								$query = mssql_query("SELECT UserUID FROM PS_GameData.dbo.UserStoredPointItems WHERE UserUID = '{$user_data['UserUID']}' AND slot = '{$s}'") or die();
								if (mssql_num_rows($query) <= 0) {
									$empty_slot = $s;
									break;
								}
							} else {
								$empty_slot = false;
								break;
							}
						}

						if ($empty_slot) {
							mssql_query("INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES ('{$user_data['UserUID']}','{$empty_slot}','{$ex_item_id[$e]}','{$ex_item_count[$e]}','" . date('Y-m-d H:i:s') . "')") or die();
						} else {
							set_msg('No empty slots for items', 'error', $back_url, 'buy_msg_' . $post_data['id']);
						}
					} else {
						$empty_slot = 1;
						mssql_query("INSERT INTO PS_GameData.dbo.UserStoredPointItems (UserUID, Slot, ItemID, ItemCount, BuyDate) VALUES ('{$user_data['UserUID']}','{$empty_slot}','{$ex_item_id[$e]}','{$ex_item_count[$e]}','" . date('Y-m-d H:i:s') . "')") or die();
					}
				}
			}
		} else{
			set_msg('No empty slots for items', 'error', $back_url, 'buy_msg_' . $post_data['id']);
		}
		
		$new_points = $user_data['Point'] - $item_data['item_price'];
		mssql_query("UPDATE PS_UserData.dbo.Users_Master SET Point = '{$new_points}' WHERE UserUID = '{$user_data['UserUID']}'") or die();
		set_msg('Items pack purchased!', 'success', $back_url, 'buy_msg_' . $post_data['id']);
	}
}
?>