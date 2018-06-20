<meta charset="utf-8">
<link rel="stylesheet" href="./css.css">
<title>home</title>
			<?php $page="productForm";include 'navbar.php'; include 'functionsIsaac.php';?>
<body>
<div class='page_content'>
    
<form action="productForm.php" method="POST" enctype="multipart/form-data">
<div id="User_Input">
<table >
<tr><td>Name:</td>	<td><input type="text" name="name" value='' id='name'>	</td></tr>
<tr><td>Description:</td>	<td><input type="text" name="description" >	</td></tr>
<tr><td>Price:</td> 	<td><input type="text" name="price" >	</td></tr>
<tr><td>Image:</td> 	<td><input type="file"  name="fileToUpload" id="fileToUpload">	</td></tr>
<tr><td>Category:</td>  
<td><?php
	$connection = getConnection();
	
	if($connection -> connect_error){
		die("Connection failed: ". $connection ->connect_error);
	}
	$query =$connection->query("SELECT * FROM CATEGORIES");
	$counter = 0;
	while($dataCat = $query->fetch_assoc()){ 
		if($counter == 3){
			echo "<br>";
			$counter = 0;
		}
		echo "<input type='checkbox' name=".$dataCat["cat_id"].">".$dataCat["cat_name"]." </input>";
		$counter++;
		}
?>
</td></tr>
<tr><td colspan="2">	<div id=submit> <input type="Submit" value="submit" id="submit1" />	</div></td></tr>
</table>
</div>
</form>
    

<?php

//getting input
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$image = $_POST['fileToUpload'];
$errors[] = array();

//checking for input. Size will be zero if the submit button HASNT been clicked
if (sizeOf($_POST) > 0){
	
	//Checking name
        $validName = checkNameProdEntry($name);
	if ( gettype($validName) === "string"){
		//name is invalid
                
		array_push ($errors, 'Invalid Name '.$validName);
	}

	//Checking description
	if ( ! checkDescripProdEntry($description)){
		//description is invalid
		array_push ($errors, 'Description too long');
	}

	//Checking price
        $validPrice = checkPriceProdEntry($price);
	if ( gettype($validPrice) === "string"){
		//price is invalid
		array_push ($errors, 'Invalid Price: '.$validPrice);
	}
	
	if ( $_FILES['fileToUpload']['error']== 0) {
            
		$imageValid = checkImage();
               
		if (!empty($imageValid)){
			
			foreach($imageValid as $imageError){
                            array_push($errors, $imageError);
                        }
		}
                
		
	} else {
                //no picture was uploaded
		array_push($errors, 'Please select an image ');
		
	}
        
        if(!checkCategories()){
            array_push ($errors, 'Please select a category');
        }

    if (sizeOf($errors) > 1){
        //errorHandler returns a js alert
            echo errorHandler($errors);
    }
    else{
        
        addProduct();
    }
}


?>
</div>
</body>
<?php include 'footer.php';?>
</html>
