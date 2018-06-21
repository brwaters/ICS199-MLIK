
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="./css.css">
<title>Register - MLIK</title>
			<?php $page="register"; include 'functions.php'; include 'navbar.php'; ?>
</head>
    <?php

        if(sizeOf($_POST) > 0){
	
            //include'functionsRegister.php';
            $errors = array();
            $fname = escapeString($_POST["fname"]);
            $lname = escapeString($_POST["lname"]);
            $address = escapeString($_POST["address"]);
            $email = escapeString($_POST["email"]);
            $city = escapeString($_POST["city"]);
            $postal = escapeString($_POST["postal"]);
            $prov = escapeString($_POST["province"]);
            $pass1 = escapeString($_POST["password1"]);
            $pass2 = escapeString($_POST["password2"]);
            // validate first name
            $valid = checkNameReg($fname);
            if(! $valid){
                array_push($errors, 'Invalid First Name');
            }
            //validate last name
            $valid = checkNameReg($lname);
            if(! $valid){
                array_push($errors, 'Invalid Last Name');
            }
            // validate email
            $valid = checkEmailReg($email);
            if(! $valid){
                array_push($errors, 'Invalid Email');
            } 
	
	    if ( ! checkForUser($email)) {

            	array_push($errors, 'Email already exists');
	     }
            //validate address
            $valid = checkNameReg($address);
            if( ! $valid ){
                array_push($errors, 'Invalid Address');
            }
                  
            
            //validate city
            $valid = checkNameReg($city);
            if( ! $valid ){
                array_push($errors, 'Invalid City');
            }
            //validate postal code
           $valid = checkPostReg($postal);
           if( ! $valid ){
                array_push($errors,'Invalid Postal Code \(We accept format: "V0G1H1\)');
            }
            // validate password
            $valid = checkPassReg($pass1,$pass2);
	    if ( !  $valid["valid"] ) {
		array_push ($errors, $valid['error']);	
	    }

	
       		//ERRRRRRROOOORRRRRSSSSS
            if (sizeOf($errors) > 0){
		    echo errorHandler($errors);
            }
            else{
                 //echo "no errors";
                echo addUser($address = $address, $fname = $fname, $lname = $lname, $pass = $pass2, $email = $email, $city = $city, $postal = $postal, $prov = $prov);
		echo errorHandler(array('Success! Redirecting to Login Page'));
                echo '<script> window.location.href = "./login.php";</script>';
            }
        }
    ?>
	
        <form action="register.php" method="POST" enctype="multipart/form-data">
<div id="User_Input">
<h1>Registration</h1>
<table > 
    <tr><th>First name:</th>	<td><input type="text" name="fname" value='' id='name'></td></tr>
    <tr><th>Last name:</th>	<td> <input type="text" name="lname" ></td></tr>
    <tr><th> Email: </th>	<td><input type="text" name="email" ></td></tr>
    <tr><th> Address: </th>	<td><input type="text" name="address" ></td></tr>
    <tr><th> City: </th>	<td><input type="text" name="city" ></td></tr>
    <tr><th> Postal Code: </th>	<td><input type="text" name="postal" ></td></tr>
    <tr><th> Province: </th>	<td><select name="province">
        <option value="BC">BC</option><option value="AB">AB</option>
        <option value="SK">SK</option><option value="MB">MB</option>
        <option value="ON">ON</option><option value="QC">QC</option>
        <option value="NL">NL</option><option value="NB">NB</option>
        <option value="PE">PE</option>
    
            </select></td></tr>
    <tr><th> Password:</th>	<td> <input type="password" name="password1" ></td></tr>
    <tr><th> Verify password:</th>	<td> <input type="password"  name="password2"></td> </tr>


    <th><td colspan="2" ><div id=submit ><input type="submit" value="Submit" id="submit1" />	</div> </th><tr>
</table>
</div>
</form>
</body>
<?php include 'footer.php';?>
</html>
