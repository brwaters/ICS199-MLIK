<?php
	session_start();
	$nav = "<header>
			<h1>The mlik header image goes here!</h1>
			<ul id = \"navbar\">
			<li><a href=\"index.php\">Home</a></li>
			<li><a href=\"products.php\">Products</a></li>
			<li><a href=\"about.php\">About</a></li>";
	


		if ($_SESSION['loggedIn']) {
			
			$nav =  $nav . "<li><a href=\"cart.php\">Cart</a></li>";

			if ($_SESSION['account_type'] == 'admin'){
				$nav =  $nav . "<li><a href=\"productForm.php\">Add Product</a></li>";
			}

                } else {
			//user is not logged in, so here they have the option to log in
			$nav =  $nav . "<li><a href=\"login.php\">Login</a></li>";
		}
	$nav = $nav . "
				</ul>
			</header>";
	$reg = "index\.php";
	$swap = "index\.php\" > here it is";
	
	//$nav1 = preg_replace($reg, $swap, $nav);
	echo $nav;


?>
