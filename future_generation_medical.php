<?php

// main controller for Example Website
// written by Yang Yang in Dec, 2015


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
					
					// setup session
					session_start();
					$_SESSION['username'] = $username;
					$page = "customer_account_info_page.template";
					
				}
				
			}
		}
		elseif ($button == "back to login") {
			$page = "login_page.template";
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
			// this button can let you view the details of the product.
			// try it by yourself...
			
		}
		elseif ($button == "remove") {
			// this button can let you remove the item which you have added into your shopping cart.
			// try it by yourself...
		}
		elseif ($button == "go shopping") {
			// re-set session of offset
			session_start();
			$_SESSION['offset'] = 0;
			
			// go to products page to shopping
			$page = "product_page.template";
		}
		elseif ($button == "edit account information") {
			// edit your account information.
			// try it by yourself...
		}
		elseif ($button == "change password") {
			// change your account password
			// try it by yourself...
		}
	}
	elseif ($action == "payment") {
		// pay for your items online
		// try it by yourself...
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
			// this button can let you view the details of the product.
			// try it by yourself...
		}
		elseif ($button == "previous") {
			// this button can let you view the products on the previous page.
			// try it by yourself...
		}
		elseif ($button == "next") {
			// this button can let you view the products on the next page.
			// try it by yourself...
		}
		elseif ($button == "Search") {
			// search the specific items in the Database
			// try it by yourself...
		}
	}
	elseif ($action == "product_detail") {
		// You need to create a template page for each product details, like name, brand, size, color and so on.
		// try it by yourself...	
	}
	include ($page);
}
else {
	$page = "login_page.template";
	include ($page);
}

?>
