<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css.css">
        <title>Order Summary</title>
                <?php $page = 'order_summary';
        include 'navbar.php'; ?>
    </head>
    <body>
    <?php
//require_once('../vendor/autoload.php');
//
//$stripe = array(
//  "secret_key"      => "sk_test_BQokikJOvBiI2HlWgH4olfQ2",
//  "publishable_key" => "pk_test_6pRNASCoBOKtIshFeQd4XMUh"
//);
//
//\Stripe\Stripe::setApiKey($stripe['secret_key']);\

$connection = new mysqli("localhost", "cst170", "381953", "ICS199Group07_dev");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
} else {
    //echo '<pre>' . print_r($_SESSION) . '</pre>';

    $queryName = "";
    $queryAddress = "SELECT fname, lname, address, city, province, postal_code FROM ICS199Group07_dev.CUSTOMERS WHERE cust_id =" . $_SESSION['cust_id'] .";";
    $queryCart = "";
    //print_r($queryAddress);
    $query = $connection->query($queryAddress);
    
    while ($orderData = $query->fetch_assoc()) {
        //print_r(orderData);
        ?>
    <div>
        <p><?php print $orderData['fname'] . ' ' . $orderData['lname']; ?></p>
        <p><?php print $orderData['address'] . ', ' . $orderData['city'] . ', ' . $orderData['province'] . ' ' . $orderData['postal_code']; ?></p>
    </div>
        <?php
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
                                $total_price = number_format((float)$total_price, 2, '.', '');
				//increasing subtotal to include this cart item
				$sub_total = $sub_total + $total_price; 



				//echoing items
				echo '
				<div class=\'cart_item\'>
				<ul class=\'cart_info\'>
					<li class=\'cart_info\' ><img src=\'product_pics/' . $prod_id . '\' class=\'cart_img\'/></li>
					<li class=\'cart_info\' >' . $prod_name . '</li>
					<li class=\'cart_info\'><form class=\'cart_info\'  action = "cart.php" method = "post"><input type="submit" name="decrementCart" value="-" /><input type="hidden" name="prod_id" value="' . $prod_id  . '"/></form>
					Qty: ' . $qty . '
					<form class=\'cart_info\'  action = "cart.php" method = "post"><input type="submit" name="incrementCart" value="+" /><input type="hidden" name= "prod_id" value="' . $prod_id  . '"/></form></li>
					<li class=\'cart_info\'  >Individual Price: ' . $single_price . '</li>
					<li class=\'cart_info\' >Price: ' . $total_price . '</li>
					<li class=\'cart_info\' ><form class=\'cart_info\'  action = "cart.php" method = "post"><input type="submit" name="removeItem" value="Remove" /><input type="hidden" name= "prod_id" value="' . $prod_id  . '"/></form></li>
				</ul>
				</div>';

			}
                        ?>
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
    <?php include 'footer.php';?>
</html>