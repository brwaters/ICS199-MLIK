<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="./css.css">
<title>home</title>
		<?php $page="login"; include 'functions.php'; include 'navbar.php'; 
		function printLogin () { 
		echo '
		<h1>LOGIN PAGE!!</h1>
		<form action="login.php" method="POST" enctype="multipart/form-data">
		<p> Username: <input type="text" name="username" id="usrname"></p>
		<p> Password: <input type="password" name="password" id="passwd"</p>
		<input type="hidden" name="accept_policy" value="off">
		<input type="submit" value="submit" id="submit1" />
		</form>';
	}
	?>
</head>
<body>

<?php
if (sizeOf($_POST) == 0){
	$_SESSION['logInFailed'] = true;
}
//variables
$conn = getConnection();
$user = escapeString($_POST['username']);
$pass = escapeString($_POST['password']);
$accept_policy = $_POST['accept_policy'];

//this is for debugging. I wrote a bunch of this when I was hungry and forgot how to logic
/*echo '<h1>ACCEPT POLICY VALUE:' .  $accept_policy . '</h1>';
if ($_SESSION['logInFailed']){
echo '<h1>LOGIN FAILED</h1>';
} else {
echo '<h1>LOGIN SUCCEEDED</h1>';
}*/


//handling privacy policy
if ($accept_policy == 'on'){

	$privacy = true;
	
} else  { 
	//if this block is being excecuted, this is the first time the user is seeing this page
	$privacy = false;
	if  (sizeOf($_POST) == 0 || $_SESSION['logInFailed']) { 
		printLogin();
	}//end accept policy off

} //end else


//only runs if there has been input
if (sizeOf($_POST) > 0 && isSet($accept_policy)){

	$login = check_login($conn, $email = $user, $pass = $pass);

	if ($login[0]){
		$_SESSION['logInFailed'] = false;
		$custID = $login[1]['cust_id'];
		//login successful [1] is the name
		
		//is priavcy policy accepted
		if(checkPolicy($custID)){
			$privacy = true;
		}
		else{
			//User has not accepted the privacy policy
			//We will now present the user with the policy and give them the option to accept it	
			echo '<h1>Privacy Policy</h1>
				<br>
				<p>Yo dawg, this be our policy of privacy. Do unto your self to take a gander, and accepteth it if thou must do eth.</p>
<form action="login.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="username" id="usrname"  value="' . $email . '">
<input type="hidden" name="password" id="passwd" value="' . $pass . '">
<p>Accept Privacy Policy <input type="checkbox" name="accept_policy">
<input type="submit" value="submit" id="submit1" />
			</form>	

				';
		}
		
		if($privacy){
			//setting session variables
			$_SESSION['loggedIn'] = true;
			$_SESSION['cust_id'] = $custID;
			$_SESSION['fname'] = $login[1]['fname'];
			$_SESSION['account_type'] = $login[1]['account_type'];
			$_SESSION['logInFailed'] = false;

			//#######################################################################################
			//THIS LINE IS COMMENTED OUT FOR TESTING PURPOSES. This needs to be uncommented when done
			//#######################################################################################
			//setPolicy($_SESSION['cust_id'], 'Y');
			//#######################################################################################

			//if user was directed from trying to add something to cart
			if ($_SESSION['addToCart']){
				echo '<script> if(window.confirm("Welcome ' . $login[1]['fname'] . '! Added product to cart!")){window.location.href=\'products.php?submit=' . $_SESSION['addToCart_prod_id'].  '\';}; </script>';
					
			}
				
			echo '<script> if(window.confirm("Welcome ' . $login[1]['fname'] . '!")){window.location.href=\'index.php\';}; </script>';
		}else{
			echo errorHandler(array("Please accept our privacy policy"));
		}
	} else {

		//login failed [1] is an array of errors
		echo errorHandler($login[1]);
		$_SESSION['logInFailed'] = true;
		//printLogin();
		
	}
}
?>
</body>
<?php include 'footer.php';?>
</html>
