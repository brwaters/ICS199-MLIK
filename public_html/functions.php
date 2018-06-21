<?php

function selectFromDB($attributes = array('*'), $table, $where = '') {

    $dbc = getConnection();
    $query = 'SELECT ';

    foreach ($attributes as &$col) {

        $query = $query . $col . ' ';
    }

    //adding table
    $query = $query . 'FROM ICS199Group07_dev.' . $table . ' ';

    //adding where clause if relevant
    if (!empty($where)) {

        $query = $query . ' ' . $where;
    }

    $result = mysqli_query($dbc, $query);
    return $result;
}

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

function getConnection() {
    $connection = new mysqli("localhost", "cst170", "381953", "ICS199Group07_dev");
    return $connection;
}

function escapeString($input) {
    $dbc = getConnection();
    return mysqli_real_escape_string($dbc, trim(strip_tags($input)));
}

function checkPolicy( $cust_id ){
	$dbc = getConnection();
        $query = "SELECT accept_policy  FROM ICS199Group07_dev.CUSTOMERS WHERE cust_id = '$cust_id'";
        $r = @mysqli_query($dbc, $query);

        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$choice = $row['accept_policy'];
	if ( $choice  === 'Y' ) {
	
		return true;
	} else {
		return false;
	}
		
}
function setPolicy( $cust_id , $val){
	if ($val != 'Y' && $val != 'N'){
		echo errorHandler(array('Error: Please set policy to Y or N'));
		return '';
	}
	$dbc = getConnection();
    	$query = 'UPDATE CUSTOMERS SET accept_policy = \'' . $val . '\' WHERE cust_id = ' . $cust_id;
        $r = @mysqli_query($dbc, $query);
		
}

function getLastLogin( $cust_id ){

    $dbc = getConnection();
    $insrt_query = 'SELECT * FROM CUSTOMERS WHERE cust_id = ' . $cust_id;
    $r = @mysqli_query($dbc, $insrt_query);
    $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
    if (! empty($row['last_login'])){
	return $row['last_login'];
	} else {
	return '';
	}

}
function setLastLogin( $cust_id ){

    $dbc = getConnection();
    $insrt_query = 'UPDATE CUSTOMERS SET last_login = sysdate() WHERE cust_id = ' . $cust_id;
    $r2 = @mysqli_query($dbc, $insrt_query);
    $mysqlErrors = $r2->error;

}
function check_login($dbc, $email = '', $pass = '') {

    //This function checks login credentials and returns an array
    //  array ( bool, arr )
    // If the bool is true, the login was successfull and the array is the customers first name and email
    // if the bool is false, the array is a list of errors that occured. Unsuccessfull logon is an error.	

    $errors = array();

    //checking information was entered
    if (empty($email)) {
        array_push($errors, 'Please enter email');
    } else {
        //password was entered
        if (!preg_match('/^(\w|\.)+@(\w|\.)+\.[a-z]+$/i', $email, $match)) {
            array_push($errors, 'Invalid Email');
        } else {

            $e = mysqli_real_escape_string($dbc, trim($email));
        }
    }
    if (empty($pass)) {
        array_push($errors, 'Please enter password');
    } else {
        $p = md5(escapeString($pass));
    }

    //checking to see if we got this far
    if (empty($errors)) {

        //no errors so far
        //now we retrieve info from db
        $query = "SELECT fname, cust_id, account_type FROM ICS199Group07_dev.CUSTOMERS WHERE email = '$e' AND passwd = '$p'";
        $r = @mysqli_query($dbc, $query);

        //checking results
        if (mysqli_num_rows($r) != 1) {

                //error wrong number of rows returned, user doesn't exist in database
            	array_push($errors, 'Wrong email or password');
        } else {
            //USER EXISTS IN DATABASE!!!
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	    $cust_id = $row['cust_id'];

           	 return array(true, $row);
        }
    }
    return array(false, $errors);
}

function typethis() {
    echo 'FUDSLKFJIDSHFPIDSHKJFHDSFFIUGDSFIDSHF';
}

function logOut() {
    unset($_SESSION['loggedIn']);
    unset($_SESSION['cust_id']);
    unset($_SESSION['fname']);
    unset($_SESSION['account_type']);
}

function addToCart($prod_id) {
    //first check that cart contains item
    $dbc = getConnection();
    $cust_id = $_SESSION['cust_id'];
    $query = 'SELECT * FROM ICS199Group07_dev.CART WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
    $r = @mysqli_query($dbc, $query);

    //checking results
    if (mysqli_num_rows($r) != 1) {
        //there should be one row returned. Return error
        echo errorHandler(array('Error updating cart, item not in cart'));
        return false;
    }


    //for verification
    $prev_qty = $r->fetch_assoc()['quantity'];


    //if we got this far that means the item is in the cart. Theres no reason for it not too because in order for this function to be executed the item would have to be in the cart
    //anyway
    //UPDATE Orders SET Quantity = Quantity + 1 WHERE ...

    $insrt_query = 'UPDATE CART SET quantity = quantity + 1 WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
    $r2 = @mysqli_query($dbc, $insrt_query);
    $mysqlErrors = $r2->error;

    if (!empty($mysqlErrors)) {
        echo errorHandler(array('Error updating cart, sql error'));
        return false;
    }

    //This is all to verify that the update worked;
    $r3 = @mysqli_query($dbc, $query);
    $cur_qty = $r3->fetch_assoc()['quantity'];

    if ($cur_qty != $prev_qty + 1) {
        // if the above statement evaluates to true, there was an issue.
        echo errorHandler(array('Error updating cart, please try again'));
        return false;
    } else {
        //everything worked!
        return true;
    }
}


function removeFromCart($prod_id) {
    //first check that cart contains item
    $dbc = getConnection();
    $cust_id = $_SESSION['cust_id'];
    $query = 'SELECT * FROM ICS199Group07_dev.CART WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
    $r = @mysqli_query($dbc, $query);

    //checking results
    if (mysqli_num_rows($r) != 1) {
        //there should be one row returned. Return error
        echo errorHandler(array('Error updating cart, item not in cart'));
        return false;
    }


    //for verification
    $prev_qty = $r->fetch_assoc()['quantity'];

    //now we need to remove the item if the qty is going from 1 to 0
    if ($prev_qty <= 1) {
        $insrt_query = 'DELETE FROM CART  WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
        $r2 = @mysqli_query($dbc, $insrt_query);
        $mysqlErrors = $r2->error;

        if (!empty($mysqlErrors)) {
            echo errorHandler(array('Error updating cart, sql error'));
            return false;
        } else {
            return true;
        }
    }


    //if we got this far that means the item is in the cart. Theres no reason for it not too because in order for this function to be executed the item would have to be in the cart
    //anyway

    $insrt_query = 'UPDATE CART SET quantity = quantity - 1 WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
    $r2 = @mysqli_query($dbc, $insrt_query);
    $mysqlErrors = $r2->error;

    if (!empty($mysqlErrors)) {
        echo errorHandler(array('Error updating cart, sql error'));
        return false;
    }

    //This is all to verify that the update worked;
    $r3 = @mysqli_query($dbc, $query);
    $cur_qty = $r3->fetch_assoc()['quantity'];

    if ($cur_qty != $prev_qty - 1) {
        // if the above statement evaluates to true, there was an issue.
        echo errorHandler(array('Error updating cart, please try again'));
        return false;
    } else {
        //everything worked!
        return true;
    }
}

function deleteFromCart($prod_id) {
    //first check that cart contains item
    $dbc = getConnection();
    $cust_id = $_SESSION['cust_id'];
    $query = 'SELECT * FROM ICS199Group07_dev.CART WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
    $r = @mysqli_query($dbc, $query);

    //checking results
    if (mysqli_num_rows($r) != 1) {
        //there should be one row returned. Return error
        echo errorHandler(array('Error updating cart, item not in cart'));
        return false;
    }


    //now we need to remove the item if the qty is going from 1 to 0
    $insrt_query = 'DELETE FROM CART  WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
    $r2 = @mysqli_query($dbc, $insrt_query);
    $mysqlErrors = $r2->error;

    if (!empty($mysqlErrors)) {
        echo errorHandler(array('Error updating cart, sql error'));
        return false;
    } else {
        return true;
    }
}

function addUser($address, $fname, $lname, $pass, $email, $city, $postal, $prov) {
    $hashedPass =  md5(escapeString($pass));
    
    if (isset($fname)) {
        $connection = getConnection();
        if ($connection->connect_error) { //show error if database connection fails
            die("Connection failed: " . $connection->connect_error);
        } else {
            //echo "Connection made: attempting Insert";
            $newUser = "INSERT INTO ICS199Group07_dev.CUSTOMERS (fname, lname, username, passwd, email, province, address, city, postal_code, account_type) VALUES ('$fname', '$lname', '$email', '$hashedPass', '$email', '$prov', '$address', '$city', '$postal', 'customer');";
            //echo $newUser;
		//return $newUser;
            if (($connection->query($newUser)) === true) {
                //echo "Added to DB.";
            }
        }
    } else {
        //echo "Didn't add to DB; fname wasn't set.";
    }
    $connection->close();    
}
function clearCart(){
		//first check that cart contains item
		$dbc = getConnection();
		$cust_id = $_SESSION['cust_id'];
		
		//now we need to remove the item if the qty is going from 1 to 0
		$insrt_query = 'DELETE FROM CART  WHERE cust_id = ' . $cust_id;
		$r2 = @mysqli_query($dbc, $insrt_query);
		$mysqlErrors = $r2->error;

		if (! empty($mysqlErrors)) {
			echo errorHandler(array('Error updating cart, sql error'));
			return false;
		} else {
			return true;
		} 
}

function makeOrder( $cust_id ){


	
	//First this function makes a receipt
	//This then function takes the items in the users cart and puts them in the PURCHASES table under a common transaction id
	//then after all of that clears the cart

	$dbc = getConnection();
	$errors = array();
	


	//ensuring customer has items in their cart
	$query = "SELECT * FROM ICS199Group07_dev.CART WHERE cust_id = '" . $cust_id . "'";
        $r = @mysqli_query($dbc, $query);

        //checking results to ensure cart isnt empty
        if (mysqli_num_rows($r) == 0) {
        	array_push($errors, 'Cart is empty');
		return $errors;
        } 

	//if we are here, the cart isn't empty

	//making a receipt yo
	$rcpt_query = 'INSERT INTO  ICS199Group07_dev.RECEIPT (time, cust_id) values ( sysdate(), ' . $cust_id . ')';
        $r2 = @mysqli_query($dbc, $rcpt_query);
	if (! $r2 ) {
  		$err =   "Error description: " . mysqli_error($dbc);
		array_push ($errors, $err);
		//return $errors;
	}

	//generating transaction id
	$query = "SELECT max(trans_id) as max_trans_id FROM ICS199Group07_dev.RECEIPT WHERE cust_id = '" . $cust_id . "'";
        $recp = @mysqli_query($dbc, $query);
        //checking results
        if (mysqli_num_rows($recp) == 0) {
		//This is the first transaction, CONGRATS!
		$trans_id = 0;
        } else {
		//This is just one of many insignifigant transactions
		$trans_id = $recp->fetch_assoc()['max_trans_id'];
	} 

	//  $r currently contains all of the cart information
	//  we need to take the information from the cart and throw that into the purchases table under the transaction id 
	while ( $cartItem = $r->fetch_assoc()){

		//getting info about the item
		$qty = $cartItem['quantity'];
		$prod_id = $cartItem['prod_id'];
		//getting price
		$query = "SELECT Price FROM ICS199Group07_dev.PRODUCTS WHERE prod_id = '" . $prod_id . "'";
		$r3 = @mysqli_query($dbc, $query);
		if (! $r3 ) {
			$err =   "Error getting information about product";
			array_push ($errors, $err);
			return $errors;
		}
		$price = $r3->fetch_assoc()['Price'];	
		
		
		//inserting item into database
		$query = "INSERT INTO ICS199Group07_dev.PURCHASES (quantity, trans_id, cust_id, prod_id, original_price) VALUES ( " . $qty . ", " . $trans_id . ", " . $cust_id . ", " . $prod_id. ", " . $price . " )";	
		$r4 = @mysqli_query($dbc, $query);
		if (! $r4 ) {
			$err =   "Error adding purchase";
			array_push ($errors, $err);
			return $errors;
		}
	}
	
		

	//removing all items from cart
	clearCart();

	//printing reciept
	 printReceipt( $trans_id, $cust_id ); 

}


function generateReceipt($trans_id, $cust_id){
	//starting output
	$output = '';

	//this returns the path to the file
	$dbc = getConnection();

	//this variable will control the output of the page
	//If this is false, the user wont see anything  on the screen
	$has_ordered = false;

	//getting list of orders by this user
	$queryRec = 'SELECT * FROM ICS199Group07_dev.RECEIPT WHERE trans_id = ' . $trans_id;
	$r = @mysqli_query($dbc, $queryRec);

	//checking if they have made any orders
	if (mysqli_num_rows($r) != 0) {
		$has_ordered = true;
	}



	if ( ! $has_ordered ){
		$output = $output .   '<p> Order Doesn\'t Exist! </p>';	
	} else {
		// managing css 	START CSS
		$output = $output . ' 
		<html>
			<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
			<head>
				<title>Order ' . $trans_id . '</title>	
			<style>
				table {
				    font-family: arial, sans-serif;
				    border-collapse: collapse;
				    width: 50%;
				    //margin: auto;
				}
				
				h1, h3 {
				    font-family: arial, sans-serif;
				}

				td, th {
				    border: 1px solid #dddddd;
				    text-align: left;
				    padding: 8px;
				}

				tr:nth-child(even) {
				    background-color: #dddddd;
				}
			</style>
			</head>
			<body>

			<h1>MLIK</h1>
			<h3>Quality Lactate Products and Services</h3>
		';

		$timeOfPurchase = $r->fetch_assoc()['time'];

		$query = 'select sum(original_price) as total from ICS199Group07_dev.PURCHASES WHERE trans_id = ' . $trans_id . ' and cust_id = ' . $cust_id  ;
		$total_query = @mysqli_query($dbc, $query);
		$total = $total_query->fetch_assoc()['total'];

							// start of order info table
		$output = $output .   '
		<table>

		<tr>
			<th>Order No</th>
			<th>' . $trans_id . '</th>
		</tr>
	
		<tr>
			<td>Time</td> 
			<td>' . $timeOfPurchase . '</td>
		</tr>
		<tr>
			<td>Order Total</td> 
			<td><b>' . $total . '</b> </p>
		</tr>
		</table>';				// end of order info table
		

		// START SHIPPING INFO
		$queryAddress = "SELECT * FROM ICS199Group07_dev.CUSTOMERS WHERE cust_id =" . $cust_id;

		$query = $dbc->query($queryAddress);

		while ($orderData = $query->fetch_assoc()) {
									// start of shipping info table
		$output = $output .   '
			<table>

				<tr>
					<th>Shipping Info</th>
				</tr>

				<tr> <td>' . $orderData['fname'] . ' ' . $orderData['lname'] . '</td> </tr>
                                <tr> <td>' . $orderData['email'] . ' </td> </tr>
				<tr> <td>' . $orderData['address'] . '</td> </tr>
				<tr> <td>' . $orderData['city'] . ', ' . $orderData['province'] . ', ' . $orderData['postal_code'] . '</td> </tr>
				<tr> <td>Canada</td> </tr>

						
			</table>
		';						// end of shipping info table 
		}  // END SHIPPING INFO

		

		//START PRODUCT INFO
								// start product into 
		$output = $output .  '

		<table>
		<tr>
			<th>Product</th>
			<th>Quantity</th>
			<th>Price</th>
		</tr>';

		$query = 'SELECT * FROM ICS199Group07_dev.PURCHASES WHERE trans_id = ' . $trans_id . ' and cust_id = ' . $cust_id . ' order by trans_id desc';
		$order = @mysqli_query($dbc, $query);

		while ($order_info = $order->fetch_assoc()){

			//getting product name
			$query = 'SELECT * FROM ICS199Group07_dev.PRODUCTS WHERE prod_id = ' . $order_info['prod_id'];
			$prod_info = @mysqli_query($dbc, $query);
			$prod_name = $prod_info->fetch_assoc()['Name'];



			$output = $output .   '<tr>';
			$output = $output .   '<td>' . $prod_name . '</td>';	
			$output = $output .   '<td>' . $order_info['quantity'] . '</td>';	
			$output = $output .   '<td>' . $order_info['original_price'] . '</td>';	
			
			$output = $output .   '</tr>';
		}
		$output = $output .   '</table><br>';
									// end product info

	} //End of else where we check if they have ordered. 

	// END OF PRODUCT INFO 

				//start of end of html document stuff
$output = $output . '
	</body>
</html>
';

				// end of end of html document stuff
return $output;
} // end printReceipt function

function printReceipt( $trans_id, $cust_id ){

		$msg = generateReceipt($trans_id, $cust_id);

		$outputFile = 'output/orderNo' . $trans_id .  '.html';

		$output = fopen($outputFile, 'w');

		fwrite($output, $msg);
		fclose($output);	
}
function printProduct($prod_id, $prod_name, $prod_price){
return "
                    <div class='product'>
                    <li class='printProductFunctions'>

                        <!-- subtle but important break -->
                        <br>

                        <!-- Here we retrieve the image based on the product id. The product with a product id of 1 will retrieve 1.jpg from the product_pics directory -->
                        <a href='product_info.php?product_id=" . $prod_id . "'>
                        <img class='prod_img' src='product_pics/" . $prod_id. ".jpg' alt='" .  $prod_name . "' >	
                        </a>
                        <p class='prod_txt'><b>" .$prod_name . "</b></p> 
                        <p class='prod_txt'>$ " . $prod_price . "</p>

                	<form action='products.php' method='GET' enctype='multipart/form-data'>
                        	<button class='prod_txt' type='Submit' name='submit' value='" . $prod_id . "'>Add to cart</button>
               		</form>
			<br/>
            </li>
                    </div>
";
}

function checkNameReg($name){
	if ( ! preg_match('/^(\w| )*$/i', $name, $match) || $name == ''){
		return false;
	} else {
		return true;
	}
}

function checkEmailReg($email){
		if ( ! preg_match('/^(\w|\.)+@(\w|\.)+\.[a-z]+$/i', $email, $match) || $email == ''){

			return false;

		} else {
			return true;	
		}
}
function checkForUser($email) {
    //first check that cart contains item
    $dbc = getConnection();
    $query = 'SELECT * FROM ICS199Group07_dev.CUSTOMERS WHERE email = \'' . $email . '\'';

    $r = @mysqli_query($dbc, $query);
    //checking results
    if (mysqli_num_rows($r) == 0) {

        return true;
    } else {
        return false;
    }
}

function checkPostReg($postal){

         //function by Roshan Bhattara(http://roshanbh.com.np)

         if(preg_match("/^([a-ceghj-npr-tv-z]){1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}$/i",$postal)) {

            return TRUE;

        } else {

            return FALSE;

        }

}
function checkPassReg($pass1, $pass2){
	//checking to see if they are the same
	if ($pass1 != $pass2){
		return array("valid"=>false, "error"=>'Passwords do not match');
	}
	
	//checking password validity
	if (  checkNameReg($pass1) && checkNameReg($pass2)){
	
		return array("valid"=>true, "error"=>'IT WORKED!');
	}

	return array("valid"=>false, "error"=>'Invalid Password');
	
}

function prodPageOutput($prod_id){
        $dbc = getConnection();
        $query = "SELECT *  FROM ICS199Group07_dev.PRODUCTS WHERE prod_id = '$prod_id'";
        $r = @mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);

        $name = $row['Name'];
        $desc = $row['Description'];
        $price = $row['Price'];

        $img_src = "product_pics/' . $prod_id . '.jpg"; 

        echo '
        <h1>Product: ' . $name  . '</h1>
        <img class="prod_img" src="product_pics/' . $prod_id . '.jpg">
        <p>Description: ' . $desc . ' </p>
        <p>Price: ' . $price . '</p>
        
        ';
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
