
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="./css.css">
<title>home</title>
			<?php $page="register"; include 'functions.php'; include 'navbar.php'; ?>
</head>
    <?php

        if(sizeOf($_POST) > 0){
	
            include'functionsRegister.php';
            $errors = array();
            $fname = escapeString($_POST["fname"]);
            $lname = escapeString($_POST["lname"]);
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
            //validate city
            $valid = checkNameReg($city);
            if( ! $valid ){
                array_push($errors, 'Invalid City');
            }
            //validate postal code
           $valid = checkPostReg($postal);
           if( ! $valid ){
                array_push($errors,'Invalid Postal code \(We accept format: "V0G1H1\)');
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
                 echo "no errors";
            }
        }
    ?>
        <form action="register.php" method="POST" enctype="multipart/form-data">
<table style="width:100%"> 
    <tr> <td>First name:</td> <td><input type="text" name="fname" value='' id='name'></td></tr>
    <tr> <td>Last name:</td><td> <input type="text" name="lname" ></td></tr>
    <tr><td> Email: </td><td><input type="text" name="email" ></td></tr>
    <tr><td> City: </td><td><input type="text" name="city" ><td></tr>
    <tr><td> Postal Code: </td><td><input type="text" name="postal" ></td></tr>
    <tr><td> Province: </td><td><select name="province">
        <option value="BC">BC</option><option value="AB">AB</option>
        <option value="SK">SK</option><option value="MB">MB</option>
        <option value="ON">ON</option><option value="QC">QC</option>
        <option value="NL">NL</option><option value="NB">NB</option>
        <option value="PE">PE</option>
    
            </select></td></tr>
    <tr><td> Password:</td><td> <input type="text" name="password1" ></td></tr>
    <tr><td> Verify password:</td><td> <input type="text"  name="password2"></td> </tr>


    <tr><td></td><td><input type="submit" value="submit" id="submit1" /> </td><tr>
</table>
</form>
</body>
<?php include 'footer.php';?>
</html>
