<!doctype html>
<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css.css">
        <title>Account - MLIK</title>
        <?php $page = 'account';
	include 'functions.php';
        include 'navbar.php'; 
		
	if ( ! $_SESSION['loggedIn'] ) {
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
	}?>
    </head>
<body>
	<h1>Account</h1>

	<br>

	<h2>Order History</h2>
	
	<?php
		//getting important variables
		$cust_id = $_SESSION['cust_id'];
		$dbc = getConnection();

		//this variable will control the output of the page
		//If this is false, the user wont see anything  on the screen
		$has_ordered = false;

		//getting list of orders by this user
		$query = 'SELECT * FROM ICS199Group07_dev.RECEIPT WHERE cust_id = ' . $cust_id . ' order by trans_id desc';
		$r = @mysqli_query($dbc, $query);

		//checking if they have made any orders
		if (mysqli_num_rows($r) != 0) {
			$has_ordered = true;
		}
	


		if ( ! $has_ordered ){
			echo '<p> No orders so far! </p>';	
		} else {
		
			while ( $recpt = $r->fetch_assoc()) {
			//this part is run FOR EACH RECEIPT
			$trans_id = $recpt['trans_id'];

				$query = 'select sum(original_price*quantity) as total from ICS199Group07_dev.PURCHASES WHERE trans_id = ' . $trans_id . ' and cust_id = ' . $cust_id  ;
				$total_query = @mysqli_query($dbc, $query);
				$total = $total_query->fetch_assoc()['total'];

			echo '<h3>Order No: ' . $trans_id . '</h3>
				<p>Time: ' . $recpt['time'] . '</p>
				<p>Total: $' . $total . ' </p>
			<table>
			<tr>
				<th>Product</th>
				<th>Quantity</th>
				<th>Price</th>
			</tr>
			';
			
			$query = 'SELECT * FROM ICS199Group07_dev.PURCHASES WHERE trans_id = ' . $trans_id . ' and cust_id = ' . $cust_id . ' order by trans_id desc';
			$order = @mysqli_query($dbc, $query);

			echo '<br>';
			while ($order_info = $order->fetch_assoc()){
				echo '<tr>';
				
				//getting product name
				$query = 'SELECT * FROM ICS199Group07_dev.PRODUCTS WHERE prod_id = ' . $order_info['prod_id'];
				$prod_info = @mysqli_query($dbc, $query);
				$prod_name = $prod_info->fetch_assoc()['Name'];

				echo '<td>' . $prod_name . '</td>';	
				echo '<td>' . $order_info['quantity'] . '</td>';	
				echo '<td>$' . $order_info['original_price'] . '</td>';	



				
				echo '</tr>';
			}
			echo '</table><br><hr>';
			
			}

		} //End of else where we check if they have ordered. 
	?>

</body>


<?php include 'footer.php'; ?>

</html>
