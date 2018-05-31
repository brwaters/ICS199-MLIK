<?php
function checkCategories(){
        $connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev"); 
        if($connection -> connect_error){
                die("Connection failed: ". $connection ->connect_error);
        } 
        $sql ="SELECT cat_id 
                FROM CATEGORIES";
        $query = $connection->query($sql);

        //loop through seeing a category check box is checked 
        while($results = $query->fetch_assoc()){
            if(isset($_POST[$results["cat_id"]])){
                    return True;

            }

        } 
        return false;
}
function checkNameProdEntry( $input ) {
        $size = strlen($input);
        if($size < 1 || $size > 46){
            return "Size inccorect";
        }
	$connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev"); 
        if($connection -> connect_error){
                die("Connection failed: ". $connection ->connect_error);
                return false;
        } 
        $sql = "SELECT prod_id
                        FROM PRODUCTS 
                        WHERE name = '$input'";
        $query =$connection->query($sql);		
        while($results = $query->fetch_all()){ 
                 $id =$results[0][0];	
        }
	if(isset($id)){
            return "Product already in database";
        }
        else{
            return true;
        }
}
function checkDescripProdEntry( $input ) {
    if(strlen($input)> 60000 || strlen($input) < 1){
        return false;
    }
    else{
         return true;
    }
}
function checkPriceProdEntry( $input ) {
    if($input <= 0){
        return "price has to be above 0";
    }
    $pattern = "/^(?!\.?$)\d*(\.\d{0,2})?$/";
    if (preg_match($pattern, $input)){ 
	  return true;
    } 
    else{ 
	  return "price must only be numbers with a possible two decimal places";
    } 
}


function errorHandler ( $errors) {

	if (sizeOf($errors) != 0 ){

		$errorText = '';
	
		foreach ($errors as &$err){
			
			if ( ! empty($err)){
			$errorText = $errorText . '\n' . $err;	
			}		
		}	

	$returnVal =  "<script> alert('" . $errorText . "'); </script>";		}
	return $returnVal;
}


function checkImage( ){
	// Returns true if valid
	// returns a list of errors if invalid
	
        $errors = array();
       
	$target_dir ="product_pics/";  ///UPADTE THIS TO DATABASE AT SOME POINT
	
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	//$target_file = $target_dir .$target_file; /// <- MUST UPDATE filename name to path/prodId.filetype
	
	

	// Check if image file is a actual image or fake image
	if(isset($_POST["fileToUpload"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			array_push( $errors, "File is not an image.");
			$uploadOk = 0;
			}
	}
     
	// Check if file already exists
	if (file_exists($target_file)) { 
          
		array_push($errors, "Sorry, file already exists.");
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
            
		array_push($errors, "Sorry, your file is too large.");
		$uploadOk = 0;
	}
	// Allow certain file formats INGNORE FOR NOW 
	
	if($imageFileType != "jpg" ) {
           
		array_push($errors, "Sorry, only JPG files are allowed.");
		$uploadOk = 0;
	} 
	// Check if $uploadOk is set to 0 by an error
     
	if ($uploadOk == 0) {
		array_push($errors, "File was not uploaded.");
                return $errors;
	// if everything is ok, try to upload file
	} else {
		return $errors;
	}
}

function getConnection () {
$connection =  new mysqli("localhost", "cst170","381953","ICS199Group07_dev");	
return $connection;
}

function check_login ($dbc, $email = '', $pass = '') {

	//This function checks login credentials and returns an array
	//  array ( bool, arr )
	// If the bool is true, the login was successfull and the array is the customers first name and email
	// if the bool is false, the array is a list of errors that occured. Unsuccessfull logon is an error.	
		
	
	$errors = array();

	//checking information was entered
	if (empty($email)){
		array_push ($errors, 'Please enter email');
	} else {
		//password was entered
		if ( ! preg_match('/^(\w|\.)+@(\w|\.)+\.[a-z]+$/i', $email, $match)){
			array_push ($errors, 'Invalid Email');
		} else { 

			$e = mysqli_real_escape_string($dbc, trim($email)); 
		}

	}
	if (empty($pass)) {
		array_push($errors, 'Please enter password');
	} else {
		$p = mysqli_real_escape_string($dbc, md5(trim($pass)));
	}
	
	//checking to see if we got this far
	if ( empty($errors)){

		//no errors so far
		//now we retrieve info from db
		$query = "SELECT fname, cust_id, account_type FROM ICS199Group07_dev.CUSTOMERS WHERE email = '$e' AND passwd = '$p'";
		$r = @mysqli_query($dbc, $query);

		//checking results
		if (mysqli_num_rows($r) != 1){
			
			//error wrong number of rows returned, user doesn't exist in database
			array_push ($errors, 'Wrong email or password');
		} else {
			//USER EXISTS IN DATABASE!!!
			$row = mysqli_fetch_array ( $r, MYSQLI_ASSOC);
			return array(true, $row);
		}
	}
	return array( false, $errors);
}
function typethis(){
	echo 'FUDSLKFJIDSHFPIDSHKJFHDSFFIUGDSFIDSHF';
}
function addToCategory($catId, $prodId, $con){
        // ***** insert insert cat and pro into products category table
      $sql = "INSERT INTO PRODUCT_CATEGORY (CATEGORIES_cat_id,PRODUCTS_prod_id)
                      VALUES ('".$catId."','".$prodId."')";
      if($con->query($sql) === TRUE){
         return;
      }
      else{
              echo "Error: ".sql . "<br>". $con ->error;
              $con ->close();
      }
}
function addProduct(){
       
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        $connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev"); 
        if($connection -> connect_error){
                die("Connection failed: ". $connection ->connect_error);
        } 
       

        // Adding Product to database
        $sql = "INSERT INTO PRODUCTS (name,description,price)
                        VALUES ('".$name."','".$description."','".$price."')";
        if($connection->query($sql) === FALSE){
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
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $target_file = $target_dir .$id.".".$imageFileType; /// <- update name to path/id.filetype

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        
        } else {
                        echo "<script> alert('Sorry, there was an unexpexted error uploading your file.(probably a premission issue)');</script>";
        }

        ///***** Add to categories*****///
        // all actegories 	
        $sql ="SELECT cat_id 
                FROM CATEGORIES";
        $query = $connection->query($sql);

        //loop through seeing if category check box is checked if so add to table
        while($results = $query->fetch_assoc()){
            if(isset($_POST[$results["cat_id"]])){
                    addToCategory( $results["cat_id"],$id,$connection);

            }

        } 
        echo"<script> alert('Product added');</script>";
}	
?>