<html>
<head>
</head>
<body>

<form action="submitproduct.php" method="POST">
<p> Name: <input type="text" name="name" value='' id='name'></p>
<p> Decription: <input type="text" name="description" ></p>
<p> Price: <input type="text" name="price" ></p>
<p> Category: <select name="category">
<?php
	$connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev");
	
	if($connection -> connect_error){
		die("Connection failed: ". $connection ->connect_error);
	}
	$query =$connection->query("SELECT * FROM CATEGORIES");
	while($dataCat = $query->fetch_assoc()){ 
		print $dataCat["cat_name"];
		echo "loop <br>";
		echo "<option value=".$dataCat["cat_id"].">".$dataCat["cat_name"]." </option>";

		}
?>
</select></p>
<p> Image: <input  name="image" type="file"> </p>
<input type="submit" value="SUBMIT" id="submit1" />
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="productForm.js"></script>

</body>
</html>