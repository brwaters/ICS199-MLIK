<!doctype html>
<html>
	<head>
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Cart - MLIK</title>
			<?php $page = "cart"; include 'navbar.php'; include 'functions.php';?>

		<link rel="stylesheet" href="./css.css">
		<?php
		//This deals with incrementing/decrementing cart items
		
		if ( ! empty($_POST)){

			if ( ! empty($_POST['incrementCart'])){
				
				if ( ! addToCart($_POST['prod_id']) ) {
					echo errorHandler(array('Error: There was an error adding to your cart.'));
				}

			} else if ( ! empty($_POST['decrementCart'])) {

				if ( ! removeFromCart($_POST['prod_id']) ) {
					echo errorHandler(array('Error: There was an error adding to your cart.'));
				}

			} else if ( ! empty($_POST['removeItem'] )) {
				
				if ( ! deleteFromCart($_POST['prod_id'])){
					echo errorHandler(array('Error: There was an error deleting that item.'));

				}

			} else if ( ! empty($_POST['clearCart'] )) {
				
				if ( ! clearCart()){
					echo errorHandler(array('You have selected to remove all items from cart'));

				}
			}
		}
		
		?>
	</head>

	<body>
	<div class='cart_content'>
	
		<?php
			//ensuring customer is logged in.
			if ( ! $_SESSION['loggedIn']) {

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
			//customer is logged in
			
			//getting cart info
			$attributes = array();
			array_push($attributes, '*');
			echo '<h1>Your Cart</h1><br>';
			
			$query = selectFromDB($attributes, 'CART', 'WHERE cust_id = ' . $_SESSION['cust_id']);	
			$sub_total = 0;
			$cartEmpty = true;
			$productHTML = "";



			
			//each iteration here is a single cart item
			while ($data = $query->fetch_assoc()){
				$cartEmpty = false;
				$prod_id = $data['prod_id']; 

				//getting information on the product for this list item
				$where = 'WHERE prod_id = ' . $prod_id;// . ' AND cust_id = ' . $_SESSION['cust_id'];
				$product = selectFromDB($attributes, 'PRODUCTS', $where)->fetch_assoc();

				//storing product information in variables to make it easier to access
				$prod_name = $product['Name'];
				$qty = $data['quantity'];
				$single_price = $product['Price'];

				//total price for this item in the cart
				$total_price = $single_price * $qty;
                                $total_price = number_format((float)$total_price, 2, '.', '');
				//increasing subtotal to include this cart item
				$sub_total = $sub_total + $total_price; 



				//echoing items
				$productHTML = $productHTML.'
				<tr>
					<td>' . $prod_name . '</td>
					<td><form class=\'cart_info\'  action = "cart.php" method = "post"><input type="submit" name="decrementCart" value="-" /><input type="hidden" name="prod_id" value="' . $prod_id  . '"/></form>' . $qty . '<form class=\'cart_info\'  action = "cart.php" method = "post"><input type="submit" name="incrementCart" value="+" /><input type="hidden" name= "prod_id" value="' . $prod_id  . '"/></form></td>
					<td>' . $single_price . '</td>
					<td>' . $total_price . '</td>
					<td><form class=\'cart_info\'  action = "cart.php" method = "post"><input type="submit" name="removeItem" value="Remove" /><input type="hidden" name= "prod_id" value="' . $prod_id  . '"/></form></td>
				</tr>

				';
				
			} //end while	
			$productHTML= $productHTML.'</table>'; //end table
			} //end else
	
	if($cartEmpty){
		echo '<h3> Cart is empty...  </h3>';
	}
	else{
		echo '
			<table id="cart_table">
			<tr>
				<th>Product</th>
				<th>Quantiy</h1>
				<th>Single Price</th>
				<th>Price</th>
				<th></th>
			</tr>		
			';
		echo $productHTML;	
		echo '<h3 align="right">Sub Total: $ ' .number_format( $sub_total, 2, '.', ''). ' </h3>
		<form  class=\'clear_cart\'  action = "cart.php" method = "post"> <div align="right"> <input type="submit" name="clearCart" value="Clear Cart"><input type="hidden" name="confirm" value="removeAllItemsFromCart"/></div></form>

		<form action = "./place_order.php" method = "post"><input type="submit" name="placeOrder" value="Place Order" /></form> ';
	} ?>
	</div>
	<?php include 'footer.php';?>
	</body>


</html>
