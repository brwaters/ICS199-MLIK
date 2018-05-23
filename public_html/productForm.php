<html>
<head>
</head>
<body>

<form action="submitproduct.php" method="POST">
<p> NAME: <input type="text" name="name" value=''></p>
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
?></select></p>
<input type="submit" value="SUBMIT" />
</form>

</body>
</html>