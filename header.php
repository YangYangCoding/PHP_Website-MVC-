<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/header.css">
</head>
<body>

<div class="header">
<ul>
  <li><a class="active" href="http://localhost:8888">Home</a></li>
  <li><a href="http://localhost:8888/about_us.html">ABOUT US</a></li>
  <li><a href="http://localhost:8888/media.html">Media</a></li>
  <li><a href="http://localhost:8888/contact_us.html">CONTACT US</a></li>
  <li>
  <?php
  if (isset($_SESSION['username'])) { ?>
  	<form name="HeaderLoginForm" method="POST" action="Main_Controller.php">
	<a>
	Dear <?php echo $_SESSION['username']; ?></a>
	<input type="hidden" name="action" value="header_login"/>
	</form>
  <?php 
  } 
  else {
  ?>
  	<a href="localhost:8888">SIGN UP</a>
  <?php
  }
  ?>
  </li>
</ul>
</div>

</body>
</html>

