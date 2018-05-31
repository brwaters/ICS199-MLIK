<?php 
function selectFromDB($attributes = array('*'), $table, $where = ''){
	
	$dbc = getConnection();
	$query = 'SELECT ';

	foreach ($attributes as &$col ){

		$query = $query . $col . ' ';

	}

	//adding table
	$query = $query . 'FROM ICS199Group07_dev.' . $table . ' ';
	
	//adding where clause if relevant
	if (! empty($where)){
		
		$query = $query . ' ' . $where;
	}

	$result = mysqli_query($dbc, $query);
	return $result;
	
}
function checkNameProdEntry( $input ) {
	return false;
}
function checkDescripProdEntry( $input ) {
	return false;
}
function checkPriceProdEntry( $input ) {
	return false;
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


function checkImage( $image ){
	// Returns true if valid
	// returns a list of errors if invalid
	$errors = array();

	//checking extention
	if ( ! preg_match('/\w+.jpg/i' , $image["name"], $match)){
		array_push($errors, 'Invalid file type, only accepts jpgs');
	}
	
	if (sizeOf($errors) > 0){
		return $errors;
	} else {
		return true;
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
function logOut(){
	$_SESSION['loggedIn'] = false;
	$_SESSION['cust_id'] = NULL;
	$_SESSION['fname'] = NULL;
        $_SESSION['account_type'] =  NULL;
}

function addToCart($prod_id){
	//first check that cart contains item
	$dbc = getConnection();
	$cust_id = $_SESSION['cust_id'];
	$query = 'SELECT * FROM ICS199Group07_dev.CART WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
	$r = @mysqli_query($dbc, $query);

	//checking results
	if (mysqli_num_rows($r) != 1){
		//there should be one row returned. Return error
		echo errorHandler(array('Error updating cart, item not in cart'));
		return false;
	} 

	
	//for verification
	$prev_qty =  $r->fetch_assoc()['quantity'];

	
	//if we got this far that means the item is in the cart. Theres no reason for it not too because in order for this function to be executed the item would have to be in the cart
	//anyway
	//UPDATE Orders SET Quantity = Quantity + 1 WHERE ...

	$insrt_query = 'UPDATE CART SET quantity = quantity + 1 WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
	$r2 = @mysqli_query($dbc, $insrt_query);
	$mysqlErrors = $r2->error;

	if (! empty($mysqlErrors)) {
		echo errorHandler(array('Error updating cart, sql error'));
		return false;
	}  

	//This is all to verify that the update worked;
	$r3 = @mysqli_query($dbc, $query);
	$cur_qty =  $r3->fetch_assoc()['quantity'];

	if ( $cur_qty != $prev_qty + 1 ){
		// if the above statement evaluates to true, there was an issue.
		echo errorHandler(array('Error updating cart, please try again'));
		return false;
	} else {
		//everything worked!
		return true;
	}	
	
}	

function removeFromCart($prod_id){
	//first check that cart contains item
	$dbc = getConnection();
	$cust_id = $_SESSION['cust_id'];
	$query = 'SELECT * FROM ICS199Group07_dev.CART WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
	$r = @mysqli_query($dbc, $query);

	//checking results
	if (mysqli_num_rows($r) != 1){
		//there should be one row returned. Return error
		echo errorHandler(array('Error updating cart, item not in cart'));
		return false;
	} 

	
	//for verification
	$prev_qty =  $r->fetch_assoc()['quantity'];

	//now we need to remove the item if the qty is going from 1 to 0
	if ($prev_qty <= 1){
		$insrt_query = 'DELETE FROM CART  WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
		$r2 = @mysqli_query($dbc, $insrt_query);
		$mysqlErrors = $r2->error;

		if (! empty($mysqlErrors)) {
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

	if (! empty($mysqlErrors)) {
		echo errorHandler(array('Error updating cart, sql error'));
		return false;
	}  

	//This is all to verify that the update worked;
	$r3 = @mysqli_query($dbc, $query);
	$cur_qty =  $r3->fetch_assoc()['quantity'];

	if ( $cur_qty != $prev_qty - 1 ){
		// if the above statement evaluates to true, there was an issue.
		echo errorHandler(array('Error updating cart, please try again'));
		return false;
	} else {
		//everything worked!
		return true;
	}	
}	

function deleteFromCart($prod_id){
	//first check that cart contains item
	$dbc = getConnection();
	$cust_id = $_SESSION['cust_id'];
	$query = 'SELECT * FROM ICS199Group07_dev.CART WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
	$r = @mysqli_query($dbc, $query);

	//checking results
	if (mysqli_num_rows($r) != 1){
		//there should be one row returned. Return error
		echo errorHandler(array('Error updating cart, item not in cart'));
		return false;
	} 

	
	//now we need to remove the item if the qty is going from 1 to 0
	$insrt_query = 'DELETE FROM CART  WHERE cust_id = ' . $cust_id . ' AND prod_id = ' . $prod_id;
	$r2 = @mysqli_query($dbc, $insrt_query);
	$mysqlErrors = $r2->error;

	if (! empty($mysqlErrors)) {
		echo errorHandler(array('Error updating cart, sql error'));
		return false;
	} else {
		return true;
	} 
}
?>

































