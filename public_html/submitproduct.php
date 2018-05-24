<?php
	$name = $_POST['name'];
	$description = $_POST['description'];
	$price = $_POST['price'];
	$category = $_POST['category'];
	$connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev");
	
	// Adding Product to database
	/*
	if($connection -> connect_error){
		die("Connection failed: ". $connection ->connect_error);
	}
	$sql = "INSERT INTO PRODUCTS (name,description,price)
			VALUES ('".$name."','".$description."','".$price."')";
	if($connection->query($sql) === TRUE){
		echo "entered";
	}
	else{
		echo "Error: ".sql . "<br>". $connection ->error;
		$connection ->close();
	}
	*/
	
	/********** uploading image   *****///////
	$target_dir ="images/";  ///UPADTE THIS TO DATABASE AT SOME POINT
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); ///Need to rename image filename 
	echo basename($_FILES["fileToUpload"]["name"]);
	echo $_FILES["fileToUpload"]["name"]. "<br>
	";
	echo $target_file;
	
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	echo $imageFileType;
	// Check if image file is a actual image or fake image
	if(isset($_POST["fileToUpload"])) {
		echo "<br> we here now";
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
			}
	}
	// Check if file already exists
	if (file_exists($target_file)) { 
		echo "<p>Sorry, file already exists.</p>";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats INGNORE FOR NOW 
	
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
		$uploadOk = 0;
	} 
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "<br>Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
	$connection ->close();
?>