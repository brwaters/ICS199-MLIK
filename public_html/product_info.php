<!doctype html>
<html>

<!--  to-do: add in description -->
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css.css">

        <title>Product - MLIK</title>
        <?php $page="product_info"; include 'navbar.php'; include 'functions.php';
	
    $product_id = $_POST['submit'];

    if (isset($product_id)) { //Once pages is reloaded by clicking add cart, a product value is passed back to this page
	 if (! $_SESSION['loggedIn']) {
	    //handing product to add to session
	    $_SESSION['addToCart'] = true;
	    $_SESSION['addToCart_prod_id'] = $product_id;

	    //customer is not logged in, we display a message and redirect them to the login page
	    echo '
		<script>
		    if (window.confirm(\'You are not logged in\')){
			window.location.href=\'login.php\';
		    } else {
			window.location.href=\'login.php\';
		    }
		</script>
		';
	} else {
		//checking if item is in cart
		$dbc = getConnection();
		$checkProdExists = "SELECT prod_id FROM ICS199Group07_dev.CART WHERE cust_id = " . $_SESSION['cust_id'] . " AND prod_id = " . $product_id . ";";                                  
		$prod_exists = $dbc->query($checkProdExists);
		if (($prod_exists->num_rows) == 1){
			addToCart($product_id);
		} else {
			$addToCart = "INSERT INTO ICS199Group07_dev.CART(quantity,cust_id,prod_id) VALUES(1," . $_SESSION['cust_id'] . "," . $product_id . ");";
			$addToCart = $dbc->query($addToCart);
		}
		echo errorHandler(array('Successfully added to cart!'));
		$prod_id = $product_id;	
		
	}	
    } 


	if (!isset($prod_id)){
	        $prod_id = $_GET['product_id']; //get product id from last page
	}

        $connection = getConnection();

        if ($connection->connect_error) { //show error if database connection fails
            die("Connection failed: " . $connection->connect_error);
        }
        ?>
    </head>

    <body>
	<div class='page_content'>
	<?php
		prodPageOutput($prod_id);	
		echo ' 
                <form action="product_info.php" method="POST" enctype="multipart/form-data">
                <button class="prod_txt" type="Submit" name="submit" value="' . $prod_id . '">Add to cart</button>
               	</form>';

	?>
	</div>
    </body>
    <?php include 'footer.php'; ?>
</html>
