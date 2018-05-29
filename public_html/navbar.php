<?php
	$nav = "<header>
				<h1>The mlik header image goes here!</h1>
				<ul id = \"navbar\">
					<li><a href=\"index.php\">Home</a></li>
					<li><a href=\"products.php\">Products</a></li>
					<li><a href=\"cart.php\">Cart</a></li>
					<li><a href=\"about.php\">About</a></li>

                     <li><a href=\"login.php\">Login</a></li>
                     <li><a href=\"productForm.php\">Add Product</a></li>

				</ul>
			</header>";
	$reg = "index\.php";
	$swap = "index\.php\" > here it is";
	
	$string = 'April 15, 2003';
	$pattern = '/(\w+) (\d+), (\d+)/i';
	$replacement = "$page";
	
	echo preg_replace($pattern, $replacement, $string);
	//$nav1 = preg_replace($reg, $swap, $nav);
	echo $nav;
	echo $nav1;


?>