<?php

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

?>
