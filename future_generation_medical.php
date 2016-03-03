<?php

// main controller for http://futuregenerationmedical.com
// written by Yang Yang for http://futuregenerationmedical.com website


include("./db_obj.php");
include("./db_function.php");
include("./validation_function.php");

$action = $_POST['action'];
$page = "";

if ($action != "") {
	if ($action == "login") {
		$page = "login_page.template";
		$username = $_POST['username']; // get login info
		$password = $_POST['password'];
		$button = $_POST['button'];
		if ($button == "login") {
			// check if user enter the information or not.
			if (empty($_POST['username']) and empty($_POST['password'])) {
				echo "Please type your username and password.";
			}
			elseif (empty($_POST['password'])) {
				echo "Please type your password.";
			}
			elseif (empty($_POST['username'])) {
				echo "Please type your username.";
			}
			else {
				// check login validation
				$valid = 0;
				$valid = login_validation($username, $password);
				if ($valid == 1) {
					// setup session
					session_start();
					$_SESSION['username'] = $username;
				
					// get the items user selected.
					$item_sel = array(); // it is a array to store pro_id, quantity and date, date store with quantity: 3~date format..
					$item_sel = get_selected_item_info($username); 
					$_SESSION['item_sel'] = $item_sel;				
				
					// go to login page.
					$page = "customer_account_info_page.template";
				}
				elseif ($valid == 2) {
					echo "We are verifying your register information, please wait for at most 12 hours, we will contact you when we done, thank you.";
				}
				else {
					echo "username or password is wrong, please enter again.";
				}
			}

		}
		elseif ($button == "register") {
			$page = "register_page.template";
		}
	}
	elseif ($action == "register") {
		$page = "register_page.template";
		
		$username = $_POST['username']; // get register info
		$password = $_POST['password'];
		$re_password = $_POST['re_password'];
		$company = $_POST['company'];
		$comp_addr = $_POST['comp_addr'];
		$assign = $_POST['assign'];
		$position = $_POST['position'];
		$phone = $_POST['phone'];
		$comp_email = $_POST['comp_email'];
		$button = $_POST['button'];
		if ($button == "register") {
			// check info typed in or not
			if (empty($_POST['username']) or empty($_POST['password']) or empty($_POST['company']) or empty($_POST['assign']) or empty($_POST['position']) or empty($_POST['phone']) or empty($_POST['comp_email'])) {
				echo "Please type in all information.";
			}
			else {
				// all info typed in
				// check validation.
				if (account_exist($username)) {
					echo "This username already exists, please chose another one.";
				}
				elseif (!email_validatin($comp_email)) {
					echo "Please type in a valid email address.";
				}
				elseif (password_repeat_same($password, $re_password) != 1) {
					echo "Please make sure you typed in the same passwords.";
				}
				elseif (size_password($password) != 1) {
					if (size_password($password) == 0) {
						echo "Your password has to be longer than 6 letters";
					}
					elseif (size_password($password) == 2) {
						echo "Your password has to be shorter than 20 letters";
					}
				}
				else {
					insert_register_info($username, $password, $company, $comp_addr, $assign, $position, $phone, $comp_email);
					//session_start(); 
					//$_SESSION['username'] = $_POST['username'];
					// we need to check the ID of the new register and then give him/her access to shopping.

					// setup session
					session_start();
					$_SESSION['username'] = $username;
					$page = "customer_account_info_page.template";
					
					//$page = "customer_account_info_page.template";
				}
				
			}
		}
		elseif ($button == "back to login") {
			$page = "login_page.template";
		}		
		
	}
	elseif ($action == "temp_register") {
		// get button info
		$button = $_POST['button'];
		if ($button == 'go shopping') {
			// re-set session of offset
			session_start();
			$_SESSION['offset'] = 0;
			//echo "re-set offset:".$_SESSION['offset'];
			// go to products page to shopping
			$page = "product_page.template";
		}
	}
	elseif ($action == "customer_account_info") {
		$page = "customer_account_info_page.template";
		// get button info
		$button = $_POST['button'];
		if ($button == "check out") {
			session_start(); 
			// check which user is going to check.
			if (isset($_SESSION['username'])) {
				$username = $_SESSION['username'];
				// get payment info from payment tb.
				$opt_payment = array();
				// opt[0] = credit card; opt[1] = check; opt[2] = paypal
				$opt_payment = payment_option_info($username);
				$_SESSION['credit'] = $opt_payment[0];
				$_SESSION['check'] = $opt_payment[1];
				$_SESSION['paypal'] = $opt_payment[2];
				
				// get the items user selected.
				$item_sel = array();
				$item_sel = get_selected_item_info($username);
				$_SESSION['item_sel'] = $item_sel;
				// go to check out page to select your payment option.
				$page = "check_out_page.template";	
			}
			else {
				echo "Please login First.";
				$page = "login_page.template";
			}
			
			
		}
		elseif ($button == "see details") {
			
			session_start();
			// get product info: id and date.
			$product_info = $_POST['pro_info'];
			list($product_id, $product_quantity, $product_date) = explode("~", $product_info);
			$username = $_SESSION['username'];
				
			$_SESSION['pro_id'] = $product_id;
			// see product details.
			
			$page = "product_detail_page.template";
			
		}
		elseif ($button == "remove") {
			if (empty($_POST['pro_info'])) {
				echo "please click the checkbox on which product you want to see.";
				
				// go back to customer_account_info_page
				$page = "customer_account_info_page.template";
			}
			else {
				session_start();
				// get product info: id and date.
				$product_info = $_POST['pro_info'];
				list($product_id, $product_quantity, $product_date) = explode("~", $product_info);
				$username = $_SESSION['username'];
				
				// get date info.
				//echo $product_date." ".$product_id." ". $username;
				// delete selected item.
				delete_added_item($username, $product_id, $product_date);
				// show the delete result.

				// re-set the info of the customer account info page
				// get the items user selected.
				$item_sel = array(); // it is a array to store pro_id, quantity and date, date store with quantity: 3~date format..
				$item_sel = get_selected_item_info($username); 
				$_SESSION['item_sel'] = $item_sel;
					
				$page = "customer_account_info_page.template";
			}
		}
		elseif ($button == "go shopping") {
			// re-set session of offset
			session_start();
			$_SESSION['offset'] = 0;
			//echo "re-set offset:".$_SESSION['offset'];
			// go to products page to shopping
			$page = "product_page.template";
		}
		elseif ($button == "edit account information") {
			// go to eadit info page.
			$page = "edit_account_info_page.template";
		}
		elseif ($button == "change password") {
			// go to change pwd page
			$page = "change_account_pwd_page.template";
		}
	}
	elseif ($action == "payment") {
		// get button info
		$button = $_POST['button'];
		session_start();
		$opt_pay = $_POST['payment_option'];
		$_SESSION['opt_pay'] = $opt_pay;
		if ($button == "pay") {
			// go to right payment opt page.
			$page = "payment_opt_page.template";
		}
		elseif ($button == "back") {
			// go back to customer_account_info_page.
			$page = "customer_account_info_page.template";
		}
	}
	elseif ($action == "payment_opt") {
		// get button info
		$button = $_POST['button'];
		if ($button == "Pay"){
			// go to selected payment option page.
			$page = "paid_page.template";
		}
		elseif ($button == "Cancel") {
			// back to customer_account_info_page
			$page = "customer_account_info_page.template";
		}
	}
	elseif ($action == "paid") {
		// get button info
		$button = $_POST['button'];
		if ($button == "Go to shopping") {
			// re-set session of offset
			session_start();
			$_SESSION['offset'] = 0;
			// go to product_page
			$page = "product_page.template";
		}
		elseif ($button == "Back to Homepage") {
			header("Location: http://www.futuregenerationmedical.com");
			exit;
		}
	}
	elseif ($action == "product") {
		// get button info
		$button = $_POST['button'];
		$num_of_items = 5;
		$offset = 0;
		if ($button == "go to shopping cart") {
			// get the items user selected.
			session_start();
			if (isset($_SESSION['username'])) { // login then check out
				$username = $_SESSION['username'];

				// get the items user selected.
				$item_sel = array(); // it is a array to store pro_id, quantity and date, date store with quantity: 3~date format..
				$item_sel = get_selected_item_info($username); 
				$_SESSION['item_sel'] = $item_sel;				
				
				// go to login page.
				$page = "customer_account_info_page.template";
			}
			else { // login first
				echo "Please login First.";
				$page = "login_page.template";
			}
			
		}
		elseif ($button == "back to shopping") {
			// re-set session of offset
			session_start();
			$_SESSION['offset'] = 0;
			// go to product_page
			$page = "product_page.template";
		}
		elseif ($button == "see details") {
			
			session_start();
			$_SESSION['pro_id'] = $_POST['pro_id'];
			// see product details.
			//print_r($_POST['pro_id']);
			
			$page = "product_detail_page.template";
			
		}
		elseif ($button == "previous") {
			session_start();
			if (isset($_SESSION['offset'])) {
				$offset = $_SESSION['offset'];
				//echo "click previous, offset val:".$offset."<br>";
			}
			if ($offset < 5) {
				$offset = 0; // check if it is at the first page.
			}
			else {
				// re-set offset
				$offset -= $num_of_items;
			}
			// set session of offset.
			$_SESSION['offset'] = $offset;
			// update added items info
			$page = "product_page.template";
		}
		elseif ($button == "next") {
			session_start();
			//echo "click next";
			$num_of_lines = num_of_lines_product();
			//echo $num_of_lines."num lines<br>";
			if (isset($_SESSION['offset'])) {
				$offset = $_SESSION['offset'];
				//echo "click next, offset val:".$offset."<br>";
			}
			if ($offset < ($num_of_lines - $num_of_items)) {  // check if it is at the last page.
				// re-set offset
				$offset += $num_of_items;
			}
			//echo "add off:".$offset."<br>";
			
			// set session of offset.
			$_SESSION['offset'] = $offset;
			// update added items info
			$page = "product_page.template";
		}
		elseif ($button == "Search") {
			
			// re-set session of offset
			session_start();
			$_SESSION['offset'] = 0;
			$_SESSION['search'] = $_POST['search'];
			
			// show search items' info
			$page = "product_page.template";
		}
	}
	elseif ($action == "product_detail") {
		// get button info
		$button = $_POST['button'];
		if ($button == "add to cart") {
			session_start();
			$username = "";
			$pro_id = "";
			$quantity = $_POST['quantity'];
			$pro_name = $_POST['pro_name'];
			// check checkbox and quantity that customer selects
			if (isset($_SESSION['username'])) { // if login, get info.
				$username = $_SESSION['username'];
				$pro_id = $_SESSION['pro_id'];
				// insert the info into db.
				insert_user_select_product_info($username, $pro_id, $quantity);
			
				echo "add successfully, you have added ".$quantity." ".$pro_name." into your shopping cart!!!";
				// update added items info
				$page = "product_detail_page.template";
			}
			else { // ask user to login.
				echo "Please login first.";
				$page = "login_page.template";
			}	
			
		}
		elseif ($button == "go to shopping cart") {
			session_start(); 
			// check which user is going to check.
			if (isset($_SESSION['username'])) {
				$username = $_SESSION['username'];
				
				// get the items user selected.
				$item_sel = array();
				$item_sel = get_selected_item_info($username);
				$_SESSION['item_sel'] = $item_sel;
				// go to check out page to select your payment option.
				$page = "customer_account_info_page.template";	
			}
			else {
				echo "Please login First.";
				$page = "login_page.template";
			}
		}
		elseif ($button == "back to shopping") {
			// re-set session of offset
			session_start();
			$_SESSION['offset'] = 0;
			// go to product_page
			$page = "product_page.template";
		}	
	}
	elseif ($action == "edit_account") {
		// get button info
		$button = $_POST['button'];
		if ($button == "edit") {
			session_start();
			if (isset($_SESSION['username'])) { // check which user is going to check.
				$username = $_SESSION['username'];
				$company = $_POST['company'];
				$comp_addr = $_POST['comp_addr'];
				$assign = $_POST['assign'];
				$position = $_POST['position'];
				$phone = $_POST['phone'];
				$comp_email = $_POST['comp_email'];
				// change info in db tb customer
				edit_account_info($username, $company, $comp_addr, $assign, $position, $phone, $comp_email);
				echo "Your account information has been saved, please check if it is correct.";
				$page = "edit_account_info_page.template";
			}
			else {
				echo "Please login First.";
				$page = "login_page.template";
			}
		}
		elseif ($button == "cancel") { // go to account info page.
			session_start(); 
			// check which user is going to check.
			if (isset($_SESSION['username'])) {
				$username = $_SESSION['username'];
				
				// get the items user selected.
				$item_sel = array();
				$item_sel = get_selected_item_info($username);
				$_SESSION['item_sel'] = $item_sel;
				// go to check out page to select your payment option.
				$page = "customer_account_info_page.template";	
			}
			else {
				echo "Please login First.";
				$page = "login_page.template";
			}
		}
	}
	elseif ($action == "change_password") {
		// get button info
		$button = $_POST['button'];
		if ($button == "change") {
			session_start();
			if (isset($_SESSION['username'])) { // check which user is going to check.
				$username = $_SESSION['username'];
				$old_password = $_POST['old_password'];
				$new_password = $_POST['new_password'];
				$re_new_password = $_POST['re_new_password'];
				// check old password if it is right or not.
				$valid = login_validation($username, $old_password);
				if ($valid == 1) {
					// check new password validation.
					if (password_repeat_same($new_password, $re_new_password) != 1) {
						echo "Please make sure you typed in the same passwords.";
						// back to change password page.
						$page = "change_account_pwd_page.template";
					}
					elseif (size_password($new_password) != 1) {
						if (size_password($new_password) == 0) {
							echo "Your password has to be longer than 6 letters";
						}
						elseif (size_password($new_password) == 2) {
							echo "Your password has to be shorter than 20 letters";
						}
						// back to change password page.
						$page = "change_account_pwd_page.template";
					}
					else {
						// change password in db tb customer
						change_password($username, $new_password);
						echo "Your new password has been set, please use it to login in the future.";
				
						// go to account info page.
						// get the items user selected.
						$item_sel = array();
						$item_sel = get_selected_item_info($username);
						$_SESSION['item_sel'] = $item_sel;
						// go to check out page to select your payment option.
						$page = "customer_account_info_page.template";
					}	
				}
				else {
					echo "your old password is not right, please try again.";
					// back to change password page.
					$page = "change_account_pwd_page.template";
				}
			}
			else {
				echo "Please login First.";
				$page = "login_page.template";
			}
		}
		elseif ($button == "cancel") { // go to account info page.
			session_start(); 
			// check which user is going to check.
			if (isset($_SESSION['username'])) {
				$username = $_SESSION['username'];
				
				// get the items user selected.
				$item_sel = array();
				$item_sel = get_selected_item_info($username);
				$_SESSION['item_sel'] = $item_sel;
				// go to check out page to select your payment option.
				$page = "customer_account_info_page.template";	
			}
			else {
				echo "Please login First.";
				$page = "login_page.template";
			}
		}
	}
	elseif ($action == "header_login") {
		$page = "customer_account_info_page.template";
	}
	include ($page);
}
else {
	$page = "login_page.template";
	include ($page);
}

?>
