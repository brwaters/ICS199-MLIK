<?php
function checkNameReg($name){
    return "NAME ERROR";
}

function checkEmailReg($email){
     if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
	  return TRUE;
	} else {
	  echo("$email is not a valid email address");
	}
}

 }
function checkCityReg($city){
      if (checkSafe($city))	{
    	return TRUE;  	
      }	else 	{
      	echo ("$city is not valid");
      }

              
}
function checkPostReg($postal){
	 //function by Roshan Bhattara(http://roshanbh.com.np)
	 if(preg_match("/^([a-ceghj-npr-tv-z]){1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}$/i",$postal)) {
	    return TRUE;
	}	else {
	    return FALSE;
	}
} 

function checkProvReg($prov){
       return TRUE;         
}

function checkPassReg($pass1,$pass2){
	if ($pass1 === $pass2) {
      return TRUE;          
	}	else 	{
  	return FALSE;
  	}
}

function checkSafe($unsafe)	{
	if (preg_match("/.*;.*/", $unsafe))	{
		return FALSE;
	}	else 	{
		return TRUE;
	}
}

?>