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
			}

			.cart_img {
				height: 150px;
				padding-left: 25px;
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

			while ($data = $query->fetch_assoc()){
				$prod_id = $data['prod_id']; 
				$where = 'WHERE prod_id = ' . $prod_id;// . ' AND cust_id = ' . $_SESSION['cust_id'];
				$product = selectFromDB($attributes, 'PRODUCTS', $where)->fetch_assoc();
				$prod_name = $product['Name'];
				$qty = $data['quantity'];
				$single_price = $product['Price'];
				$total_price = $single_price * $qty;

				echo '<div class=\'cart_item\'>';
				echo '<img src=\'product_pics/' . $prod_id . '\' class=\'cart_img\'/>';
				echo $prod_name;
				echo '<br> Qty: ' .$qty;
				echo '<br> Price: ' .$total_price;

				echo '</div>';
				
			} //end while	
			} //end else
		?>


	</body>

<?php include 'footer.php';?>
</html>
