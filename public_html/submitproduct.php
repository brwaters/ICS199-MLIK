<?php
	$name = $_POST['name'];
	$description = $_POST['description'];
	$price = $_POST['price'];
	$category = $_POST['category'];
	$connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev");
	
	if($connection -> connect_error){
		die("Connection failed: ". $connection ->connect_error);
	}
	$sql = "INSERT INTO PRODUCTS (name,description,price,categories_cat_id)
			VALUES ('".$name."','".$description."','".$price."','".$category."')";
	if($connection->query($sql) === TRUE){
		echo "entered";
	}
	else{
		echo "Error: ".sql . "<br>". $connection ->error;
	}
	$connection ->close();
?>