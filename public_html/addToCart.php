<?php
session_start();
$user_id = $_SESSION['cust_id'];
$product_id = $_POST['submit'];
echo 'Product ID:' . $product_id;
$connection = new mysqli("localhost", "cst170", "381953", "ICS199Group07_dev");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$addToCart = "INSERT INTO ICS199Group07_dev.CART(quantity,cust_id,prod_id) VALUES(1," . $user_id . "," . $product_id . ");";
$incrementQuantity = "UPDATE ICS199Group07_dev.CART SET quantity = quantity + 1 WHERE cust_id = " . $user_id . " AND prod_id = " . $product_id . ";";
$checkProdExists = "SELECT prod_id FROM ICS199Group07_dev.CART WHERE cust_id = " . $user_id . " AND prod_id = " . $product_id . ";";

$prodExists = $connection->query($checkProdExists);
print_r($_SESSION);
//echo $prodExists->num_rows;
if (($prodExists->num_rows) == 1) {
    echo "There is an entry already, incrementing by 1:";
    if ($connection->query($incrementQuantity) === TRUE) {
        echo "Incremented cart value";
    } else {
        echo "Error: " . sql . "<br>" . $connection->error;
        $connection->close();
    }
} else {
    echo "No entry exists.";
    if ($connection->query($addToCart) === TRUE) {
        echo "Added to cart.";
    } else {
        echo "Error: " . sql . "<br>" . $connection->error;
        $connection->close();
    }
}

mysqli_close($connection);
?>