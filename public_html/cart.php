<!doctype html>
<html>
	<head>
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="./css.css">
		<title>Cart - MLIK</title>
			<?php $page = "cart"; include 'navbar.php'; include 'functions.php';?>

		<style>
			.cart_item {
				width: 100%;
				margin: 10px;
				background-color: grey;
				padding: 10px;	
				padding-bottom: 30px;	
			}

			.cart_img {
				height: 150px;
				//padding-left: 25px;
			}	
			
			.cart_info {
				display: inline;
				list-style-type: none;

				//padding-left: 50px;
			}
		</style>
	</head>

	<body>
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
			echo '<br>';
			
			$query = selectFromDB($attributes, 'CART', 'WHERE cust_id = ' . $_SESSION['cust_id']);	
			$sub_total = 0;

			//each iteration here is a single cart item
			while ($data = $query->fetch_assoc()){
				
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

				//increasing subtotal to include this cart item
				$sub_total = $sub_total + $total_price; 



				//echoing items
				echo '
				<div class=\'cart_item\'>
				<ul class=\'cart_info\'>
					<li><img src=\'product_pics/' . $prod_id . '\' class=\'cart_img\'/></li>
					<li>' . $prod_name . '</li>
					<li>Qty: ' . $qty . '</li>
					<li>Price: ' . $total_price . '</li>
				</ul>
				</div>';

			} //end while	
			} //end else
		?>
		<h3>Sub Total: $<?php echo $sub_total; ?> </h3>	

	</body>

<?php include 'footer.php';?>
</html>
