<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="./css.css">
<title>home</title>
		<?php $page="login"; include 'functions.php'; include 'navbar.php'; 
		
			echo '
		<h1>LOGIN PAGE!!</h1>
		<form action="login.php" method="POST" enctype="multipart/form-data">
		<p> Username: <input type="text" name="username" id="usrname"></p>
		<p> Password: <input type="password" name="password" id="passwd"</p>
		<p>Accept our <a href="privacyPolicy.php">Privacy Policy</a> <input type="checkbox" name="accept_policy" checked>
		<input type="submit" value="submit" id="submit1" />
		</form>';
		
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
$privacy = false;



//handling privacy policy
if ($accept_policy == 'on'){

	$privacy = true;
	
} 


//only runs if there has been input
if (sizeOf($_POST) > 0){

	$login = check_login($conn, $email = $user, $pass = $pass);

	if ($login[0]){
		$_SESSION['logInFailed'] = false;
		$custID = $login[1]['cust_id'];
		//login successful [1] is the name
		
		//is priavcy policy accepted
		
	
		if($privacy){
			//setting session variables
			$_SESSION['loggedIn'] = true;
			$_SESSION['cust_id'] = $custID;
			$_SESSION['fname'] = $login[1]['fname'];
			$_SESSION['account_type'] = $login[1]['account_type'];
			$_SESSION['logInFailed'] = false;
			
			
			setPolicy($_SESSION['cust_id'], 'Y');
			//setting last login
			setLastLogin($custID);
			

			//if user was directed from trying to add something to cart
			if ($_SESSION['addToCart']){
				echo '<script> if(window.confirm("Welcome ' . $login[1]['fname'] . '! Added product to cart!")){window.location.href=\'products.php?submit=' . $_SESSION['addToCart_prod_id'].  '\';}; </script>';
					
			}
				
			echo '<script> if(window.confirm("Welcome ' . $login[1]['fname'] . '!\n' . getLastLogin($custID) .  ' ")){window.location.href=\'index.php\';}; </script>';

			
		}else{
			echo errorHandler(array("Please accept our privacy policy"));
			setPolicy($custID, 'N');
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
