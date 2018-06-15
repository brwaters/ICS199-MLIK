<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css.css">
        <title>Order Summary</title>
        <?php
        $page = 'order_summary';
        include 'navbar.php';
        include 'functions.php';
        ?>
    </head>
    <body>
        <?php
        $connection = getConnection();
		

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        } else {
            //echo '<pre>' . print_r($_SESSION) . '</pre>';

            $queryName = "";
            $queryAddress = "SELECT * FROM ICS199Group07_dev.CUSTOMERS WHERE cust_id =" . $_SESSION['cust_id'] . ";";

            //print_r($queryAddress);
            $query = $connection->query($queryAddress);

            while ($orderData = $query->fetch_assoc()) {
                //print_r(orderData);
                ?>
                <div>
                    <h1>Order Summary</h1>
		    <?php
	
			// START SHIPPING INFO

                    /*<p>Name: <?php print $orderData['fname'] . ' ' . $orderData['lname']; ?></p>
                    <p>Address: <?php print $orderData['address'] . ', ' . $orderData['city'] . ', ' . $orderData['province'] . ' ' . $orderData['postal_code']; ?></p> */
		    
			echo '
				<table>

					<tr>
						<th>Shipping Info</th>
					</tr>

					<tr> <td>' . $orderData['fname'] . ' ' . $orderData['lname'] . '</td> </tr>
					<tr> <td>' . $orderData['address'] . '</td> </tr>
					<tr> <td>' . $orderData['city'] . ', ' . $orderData['province'] . ', ' . $orderData['postal_code'] . '</td> </tr>
					<tr> <td>Canada</td> </tr>

							
				</table>
				<br>
			'; 
			// END SHIPPING INFO
			?>
			
			
	
                </div>
                <?php
                //$attributes = array();
                //array_push($attributes, '*');
                //$query = selectFromDB($attributes, 'CART', 'WHERE cust_id = ' . $_SESSION['cust_id']);
                $sub_total = 0;
                $queryCart = "SELECT * FROM ICS199Group07_dev.CART WHERE cust_id =" . $_SESSION['cust_id'] . ";";
                //print_r($queryCart);
                $query = $connection->query($queryCart);
                //each iteration here is a single cart item




	
		//	STARTING TABLE 

		echo '<table>
		<tr>
			<th>Product</th>
			<th>Quantity</th>
			<th>Individual Price</th>
			<th>Price</th>
		</tr>
		';

                while ($cartData = $query->fetch_assoc()) {

                    $prod_id = $cartData['prod_id'];

                    $queryProduct = "SELECT * FROM ICS199Group07_dev.PRODUCTS WHERE prod_id = " . $prod_id . ";";
                    $querySummary = $connection->query($queryProduct);
                    $product = $querySummary->fetch_assoc();
                    $prod_name = $product['Name'];
                    $qty = $cartData['quantity'];
                    $single_price = $product['Price'];

                    //total price for this item in the cart
                    $total_price = $single_price * $qty;
                    $total_price = number_format((float) $total_price, 2, '.', '');
                    //increasing subtotal to include this cart item
                    $sub_total = $sub_total + $total_price;

                    //echoing items
		    echo '
			<tr>
				<td> ' . $prod_name . ' </td> 
				<td> ' . $qty . ' </td> 
				<td> ' . $single_price . ' </td> 
				<td> ' . $total_price . ' </td> 
			</tr>
			

			'; //end echo 
                }
		
		echo '</table>';
		//  		END TABLE
		//get user info
		$queryCus = "SELECT * FROM ICS199Group07_dev.CUSTOMERS WHERE cust_id = " .  $_SESSION['cust_id'] . ";";
        $queryResults = $connection->query($queryCus);
        $customer = $queryResults->fetch_assoc();
		$customerEmail = $customer['username'];
		echo $customerEmail;
                require_once('config.php'); 
                $sub_total = number_format((float) $sub_total, 2, '.', '');
                ?>
                <h3>Sub Total: $<?php echo $sub_total; ?> </h3>
                <form action="charge.php" method="post">
                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="<?php echo $stripe['publishable_key']; ?>"
                            data-description="<?php echo 'Payment Form'; ?>"
                            data-amount="<?php echo $sub_total * 100; ?>"
                        data-locale="auto"
						data-email="<?php echo $customerEmail;?>"></script>
                    <input type="hidden" name="sub_total" value="<?php echo $sub_total; ?>" />
                </form>
                <?php
            }

//    if ($connection->query($queryAddress) === TRUE) {
//        echo $connection->query($queryAddress);
//    } else {
//        //echo "Error: " . sql . "<br>" . $connection->error;
//        $connection->close();
//    }
        }
        ?>
    </body>
    <?php include 'footer.php'; ?>
</html>
