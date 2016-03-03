<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/header.css">
</head>
<body>

<div class="header">
<ul>
  <li><a class="active" href="http://www.futuregenerationmedical.com">Home</a></li>
  <li><a href="http://futuregenerationmedical.com/about_us.html">ABOUT US</a></li>
  <li><a href="http://futuregenerationmedical.com/who_we_are.html">WHO WE ARE</a></li>
  <li><a href="http://futuregenerationmedical.com/services.html">SERVICES</a></li>
  <li><a href="http://futuregenerationmedical.com/cgi-bin/future_gm_product_nologin.php">INVENTORY</a></li>
  <li><a href="http://futuregenerationmedical.com/contact_us.html">CONTACT US</a></li>
  <li>
  <?php
  if (isset($_SESSION['username'])) { ?>
  	<form name="HeaderLoginForm" method="POST" action="future_generation_medical.php">
	<a>
	Dear <?php echo $_SESSION['username']; ?></a>
	<input type="hidden" name="action" value="header_login"/>
	</form>
  <?php 
  } 
  else {
  ?>
  	<a href="http://futuregenerationmedical.com/cgi-bin/future_generation_medical.php">SIGN UP</a>
  <?php
  }
  ?>
  </li>
  <!--
  <ul style="float:right;list-style-type:none;">
    <li><a href="#about">About</a></li>
    <li><a href="#login">Login</a></li>
  </ul>
  -->
</ul>
</div>

</body>
</html>

