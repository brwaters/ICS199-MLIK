<?php
$user_id = 2;
$product_id = $_POST['product_id'];
$connection = new mysqli("localhost", "cst170", "381953", "ICS199Group07_dev");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

//if (check_login) {
    "INSERT INTO `ICS199Group07_dev`.`CART`(`quantity`,`cust_id`,`prod_id`)VALUES('1','" . $user_id . "','1');";
//}
mysqli_close($connection);
echo $product_id;
?>