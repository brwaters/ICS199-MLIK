<?php
$user_id = 2;
$product_id = $_POST['submit'];
echo 'Product ID:' . $product_id;
$connection = new mysqli("localhost", "cst170", "381953", "ICS199Group07_dev");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

//if (check_login) {
  $addToCart =  "INSERT INTO `ICS199Group07_dev`.`CART`(`quantity`,`cust_id`,`prod_id`)VALUES('2','" . $user_id . "','" . $product_id . "');";
  
  if($connection->query($addToCart) === TRUE){
		echo "Added:";
	}
	else{
		echo "Error: ".sql . "<br>". $connection ->error;
		$connection ->close();
	}
//}
mysqli_close($connection);
?>