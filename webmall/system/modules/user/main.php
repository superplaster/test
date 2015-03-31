<?php
$item_image = '';
$cat = get_url_param('cat');
if (!_is_int($cat)) redirect('index.php?cat=1', true);

if ($cat) $_cat = 'cat=' . $cat . '&'; else $_cat = '';

$query = mysql_query("SELECT * FROM donate_items WHERE item_cat = '{$cat}'");
$Num_Rows = mysql_num_rows($query);

$page = (isset($_GET['page'])) ? $_GET['page'] : '';

$Per_Page = 12;

$pagination = new Pagination();
$pagination->setLink("index.php?{$_cat}page=%s");
$pagination->setPage($page);
$pagination->setSize($Per_Page);
$pagination->setTotalRecords($Num_Rows);

$query = mysql_query("SELECT * FROM donate_items WHERE item_cat = '{$cat}' " . $pagination->getLimitSql()) or die(mysql_error());

if ($Num_Rows > 0) {
	$m = 0;
	$row = array();
	while ($row = mysql_fetch_array($query)) {
		$m++;
		
		if ($row['item_image']) {
			if (file_exists(SYS_ROOT . 'user/images/shop_icons/' . $row['item_image'])) {
				$item_image = "<img src='{$config['home_url']}/user/images/shop_icons/{$row['item_image']}' alt='' />";
			}
		}
	
		echo "<form action='{$config['home_url']}/index.php?validate=buy' method='post' onsubmit='this.js_enabled.value=1; return true;'>
		<input type='hidden' name='js_enabled' value='0' />
		<input type='hidden' name='back_url' value='" . back_url() . "' />";
		echo "<input type='hidden' name='id' value='{$row['id']}' />";
		
		if ($row['item_pack'] == 0) {
			echo "<div id='shop_item_box'>
				<div class='title'><b>{$row['item_name']}</b></div>
				<div class='about'>{$item_image}</div>
				<div class='price'>Price: {$row['item_price']} Count: {$row['item_count']}</div>
				<div class='buy_button'><input type='submit' name='buy' class='button green' onclick='return showLoader();' value='Buy!'></div>";
			echo get_msg('buy_msg_' . $row['id'], 'clear: both; margin-top: 10px; margin-bottom: -5px;'); 
			echo "</div>";
			
			if ($m % 3 === 0) echo "<div class='clear'></div>";
		} else {
			$ex_item_name = explode(',', $row['item_name']);
			$ex_item_count = explode(',', $row['item_count']);
			$count = count($ex_item_name)-1;
			$in_pack = '';
			for($i=0;$i<=$count;$i++)
				$in_pack .= "{$ex_item_name[$i]} ({$ex_item_count[$i]}),";
			
			if (substr($in_pack, -1) == ',') $in_pack = substr($in_pack, 0, -1);
			$in_pack = str_replace(',', ', ', $in_pack);
			
			echo "<div id='shop_item_box'>
				<div class='title'><b>Items pack! <br />In pack:</b> <a href='#' title='{$in_pack}'>view</a></div>
				<div class='about'>{$item_image}</div>
				<div class='price'>Pack price: {$row['item_price']}</div>
				<div class='buy_button'><input type='submit' name='buy' class='button green' onclick='return showLoader();' value='Buy!'></div>";
			echo get_msg('buy_msg_' . $row['id'], 'clear: both; margin-top: 10px; margin-bottom: -5px;'); 
			echo "</div>";
			
			if ($m % 4 === 0) echo "<div class='clear'></div>";
		}
			
		echo "</form>";
		
	}
} else {
	echo "<div style='text-align: center; margin-top: 30px;'>Sorry, no items to buy!</div>";
}

echo "<div class='clear'></div>";

$pagination = $pagination->create_links();

if (!empty($pagination)) {
	echo $pagination;
}

echo "<div class='clear'></div>";
?>