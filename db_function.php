<?php

// this file contains all the db functions that main controller(future_generation_medical.php) needs.

// remove an added item from tb product_selected
function delete_added_item($usr, $pro_id, $pro_date) {
	// init Database info
	$db_host = "50.62.209.83:3306";
	$db_name = "FGM";
	$db_user = "yangyang";
	$db_password = "Fgm_sql";
	// inti DB connection
	$db_con = "";

	// connect to db
	$db_con = @mysql_connect($db_host,$db_user,$db_password);
	// Check connection
	if (mysql_error()) {
		die("Failed to connect to MySQL using the PHP mysql extension: " . mysql_error());
	}
	//select DB
	mysql_select_db($db_name, $db_con);
	$query = "DELETE FROM product_selected WHERE username = '$usr' AND product_id = '$pro_id' AND date = '$pro_date'";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
	// close db_connection.
	mysql_close($db_con);
}

// insert the selected products into tb product_selected
function insert_user_select_product_info($usr, $pro_id, $pro_quan)  {
	// init Database info
	$db_host = "50.62.209.83:3306";
	$db_name = "FGM";
	$db_user = "yangyang";
	$db_password = "Fgm_sql";
	// inti DB connection
	$db_con = "";

	// connect to db
	$db_con = @mysql_connect($db_host,$db_user,$db_password);
	// Check connection
	if (mysql_error()) {
		die("Failed to connect to MySQL using the PHP mysql extension: " . mysql_error());
	}
	//select DB
	mysql_select_db($db_name, $db_con);

	//print_r($fun_add_item);
	// insert product selected info into tb product_selected.	
	$query = "INSERT INTO product_selected (username, product_id, quantity, date) VALUES ('$usr', '$pro_id', '$pro_quan', NOW())";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
	
	// close db_connection.
	mysql_close($db_con);
	
}

// get total number of lines in tb product
function num_of_lines_product() {
	// init Database info
	$db_host = "50.62.209.83:3306";
	$db_name = "FGM";
	$db_user = "yangyang";
	$db_password = "Fgm_sql";
	// inti DB connection
	$db_con = "";

	// connect to db
	$db_con = @mysql_connect($db_host,$db_user,$db_password);
	// Check connection
	if (mysql_error()) {
		die("Failed to connect to MySQL using the PHP mysql extension: " . mysql_error());
	}
	//select DB
	mysql_select_db($db_name, $db_con);
	
	// search product the username selected from tb prodcut_selected..
	
	$query = "SELECT * FROM product";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
	$num_rows = mysql_num_rows($result);

	// close db_connection.
	mysql_close($db_con);
	return $num_rows;
	
}

// get selected item info names. Return an array contains items' names.
function get_selected_item_info($usr) {
	// init Database info
	$db_host = "50.62.209.83:3306";
	$db_name = "FGM";
	$db_user = "yangyang";
	$db_password = "Fgm_sql";
	// inti DB connection
	$db_con = "";

	// connect to db
	$db_con = @mysql_connect($db_host,$db_user,$db_password);
	// Check connection
	if (mysql_error()) {
		die("Failed to connect to MySQL using the PHP mysql extension: " . mysql_error());
	}
	//select DB
	mysql_select_db($db_name, $db_con);
	
	// search product the username selected from tb prodcut_selected..
	
	$query = "SELECT * FROM product_selected WHERE username = '$usr'";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
	$item_selected = array();
	while($row = mysql_fetch_array($result)) {
		$item_selected[$row["product_id"]] = $row["quantity"]."~".$row["date"];
    	}
    	//print_r ($item_selected);
    	// close db_connection.
	mysql_close($db_con);
    	return $item_selected;
}

// check payment options of a user
function payment_option_info($usr) {
	$opt = array();
	$opt[0] = 0;
	$opt[1] = 0;
	$opt[2] = 0;
	// init Database info
	$db_host = "50.62.209.83:3306";
	$db_name = "FGM";
	$db_user = "yangyang";
	$db_password = "Fgm_sql";
	// inti DB connection
	$db_con = "";

	// connect to db
	$db_con = @mysql_connect($db_host,$db_user,$db_password);
	// Check connection
	if (mysql_error()) {
		die("Failed to connect to MySQL using the PHP mysql extension: " . mysql_error());
	}
	//select DB
	mysql_select_db($db_name, $db_con);
	
	// search payment option.
	// search credit tb first.
	$query = "SELECT * FROM pay_credit WHERE username = '$usr'";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
	// put result into opt array
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
		$opt[0] = 1;
	}
	
	//search check tb second.
	$query = "SELECT * FROM pay_check WHERE username = '$usr'";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
	// put result into opt array
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
		$opt[1] = 1;
	}

	//search paypal tb thirdly
	$query = "SELECT * FROM pay_paypal WHERE username = '$usr'";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
	// put result into opt array
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
		$opt[2] = 1;
	}
	// close db_connection.
	mysql_close($db_con);
	
	return $opt;
}


// insert register info into DB.
function insert_register_info($usr, $pwd, $comp, $comp_addr, $assi, $pos, $pho, $comp_e) {
	// init Database info
	$db_host = "50.62.209.83:3306";
	$db_name = "FGM";
	$db_user = "yangyang";
	$db_password = "Fgm_sql";
	// inti DB connection
	$db_con = "";

	// connect to db
	$db_con = @mysql_connect($db_host,$db_user,$db_password);
	// Check connection
	if (mysql_error()) {
		die("Failed to connect to MySQL using the PHP mysql extension: " . mysql_error());
	}
	//select DB
	mysql_select_db($db_name, $db_con);
	// insert info.
	$query = "INSERT INTO customer (username, password, company_name, comp_addr, assign, position, phone_num, email_addr, temp) VALUES ('$usr', '$pwd', '$comp', '$comp_addr', '$assi', '$pos', '$pho', '$comp_e', '1')";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
	mysql_close($db_con);
}

// check login info validation
function login_validation($usr, $pwd) {
	// init Database info
	$db_host = "50.62.209.83:3306";
	$db_name = "FGM";
	$db_user = "yangyang";
	$db_password = "Fgm_sql";
	// inti DB connection
	$db_con = "";

	// connect to db
	$db_con = @mysql_connect($db_host,$db_user,$db_password);
	// Check connection
	if (mysql_error()) {
		die("Failed to connect to MySQL using the PHP mysql extension: " . mysql_error());
	}
	//select DB
	mysql_select_db($db_name, $db_con);
	//setup query to check validation.
	$query = "SELECT * FROM customer WHERE username = '$usr' AND password = '$pwd'";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
	$temp = 1;
	while($row = mysql_fetch_array($result)) {
       		$temp = $row["temp"];
    	}
	
	mysql_close($db_con);
	$num_rows = mysql_num_rows($result);
	//check $result, if only one line found, ture.
	if ($num_rows == 1) {
		if ($temp == 0) {
			return 1; // finish verify ID, and account, pwd are both right
		}
		elseif ($temp == 1) {
			return 1; // still during verify ID process
		}
	}
	else {
		return 0; // account or pwd wrong.
	}
	
}


// edit customer account information.
function edit_account_info($usr, $comp, $comp_addr, $assi, $pos, $pho, $comp_e) {
	// init Database info
	$db_host = "50.62.209.83:3306";
	$db_name = "FGM";
	$db_user = "yangyang";
	$db_password = "Fgm_sql";
	// inti DB connection
	$db_con = "";

	// connect to db
	$db_con = @mysql_connect($db_host,$db_user,$db_password);
	// Check connection
	if (mysql_error()) {
		die("Failed to connect to MySQL using the PHP mysql extension: " . mysql_error());
	}
	//select DB
	mysql_select_db($db_name, $db_con);
	//setup query to check validation.
	$query = "UPDATE customer SET company_name='$comp', comp_addr='$comp_addr', assign='$assi', position='$pos', phone_num='$pho', email_addr='$comp_e' WHERE username='$usr'";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
}

function change_password($usr, $pwd) {
	// init Database info
	$db_host = "50.62.209.83:3306";
	$db_name = "FGM";
	$db_user = "yangyang";
	$db_password = "Fgm_sql";
	// inti DB connection
	$db_con = "";

	// connect to db
	$db_con = @mysql_connect($db_host,$db_user,$db_password);
	// Check connection
	if (mysql_error()) {
		die("Failed to connect to MySQL using the PHP mysql extension: " . mysql_error());
	}
	//select DB
	mysql_select_db($db_name, $db_con);
	//setup query to check validation.
	$query = "UPDATE customer SET password='$pwd' WHERE username='$usr'";
	$result = mysql_query($query) or die($query."<br/><br/>".mysql_error());
}
?>
