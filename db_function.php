<?php

// this file contains all the db functions that main controller(Main_Conroller.php) needs.

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
		return 1; // account and pwd are all right.
	}
	else {
		return 0; // account or pwd wrong.
	}
	
}

// edit customer account information.
function edit_account_info($usr, $comp, $comp_addr, $assi, $pos, $pho, $comp_e) {
	// change(edit) your account info, you can delete or change it as your demands.
	// try it by yourself...
}

function change_password($usr, $pwd) {
	// change your account password, you can delete or change it as your demands.
	// try it by yourself...
}

// remove an added item from tb product_selected
function delete_added_item($usr, $pro_id) {
	// it is a example function, you can change it or delete it as your demands.
	// try it by yourself...
}

?>
