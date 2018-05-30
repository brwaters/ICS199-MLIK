<?php
        function addToCategory($catId, $prodId, $con){
              // ***** insert insert cat and pro into products category table
            $sql = "INSERT INTO PRODUCT_CATEGORY (CATEGORIES_cat_id,PRODUCTS_prod_id)
                            VALUES ('".$catId."','".$prodId."')";
            if($con->query($sql) === TRUE){
		echo "entered";
            }
            else{
                    echo "Error: ".sql . "<br>". $con ->error;
                    $con ->close();
            }
        }
            
	$name = $_POST['name'];
	$description = $_POST['description'];
	$price = $_POST['price'];
	
	$connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev"); 
	if($connection -> connect_error){
		die("Connection failed: ". $connection ->connect_error);
	} 
	
	//***** get product id of product just entered ******////

	$sql = "SELECT prod_id
			FROM PRODUCTS 
			WHERE name = '$name'";
	$query =$connection->query($sql);		
	while($results = $query->fetch_assoc()){ 
		 $id =$results["prod_id"];	
	}
        echo "id = $id name = $name <br>";
	
        // all actegories 
        $sql ="SELECT cat_id 
		FROM CATEGORIES";
	$query = $connection->query($sql);
       
        //loop through seeing if category check box is checked if so add to table
	while($results = $query->fetch_assoc()){
                if(isset($_POST[$results["cat_id"]])){
                    addToCategory( $results["cat_id"],$id,$connection);
                    echo $results["cat_id"];
                }
                
	} 
       
        
?> <!--
        // Adding Product to database
	$sql = "INSERT INTO PRODUCTS (name,description,price)
			VALUES ('".$name."','".$description."','".$price."')";
	if($connection->query($sql) === TRUE){
		echo "entered";
	}
	else{
		echo "Error: ".sql . "<br>". $connection ->error;
		$connection ->close();
	}
	

	

	//***** get product id of product just entered ******////

	$sql = "SELECT prod_id
			FROM PRODUCTS 
			WHERE name = '$name'";
	$query =$connection->query($sql);		
	while($results = $query->fetch_all()){ 
		 $id =$results[0][0];	
	}
 
 
	

	
	/********** uploading image   *****/////// 
	$target_dir ="product_pics/";  ///UPADTE THIS TO DATABASE AT SOME POINT
	
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$target_file = $target_dir .$id.".".$imageFileType; /// <- update name to path/id.filetype
	echo "<br>target_file = $target_file<br>";
	
	
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
	
	if($imageFileType != "jpg" ) {
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
-->

