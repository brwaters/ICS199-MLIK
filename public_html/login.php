<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="./css.css">
<title>home</title>
			<?php $page="login"; include 'functionsIsaac.php'; include 'navbar.php'; ?>
</head>
<body>
<h1>LOGIN PAGE!!</h1>

<form action="login.php" method="POST" enctype="multipart/form-data">
<p> Username: <input type="text" name="username" id='usrname'></p>
<p> Password: <input type="password" name="password" id='passwd'></p>
<input type="submit" value="submit" id="submit1" />
</form>

<?php

//variables
$conn = getConnection();
$user = escapeString($_POST['username']);
$pass = escapeString($_POST['password']);


//only runs if there has been input
if (sizeOf($_POST) > 0 ){

	$login = check_login($conn, $email = $user, $pass = $pass);
	

	if ($login[0]){
		//login successful [1] is the name

		
		//setting session variables
		$_SESSION['loggedIn'] = true;
		$_SESSION['cust_id'] = $login[1]['cust_id'];
		$_SESSION['fname'] = $login[1]['fname'];
		$_SESSION['account_type'] = $login[1]['account_type'];

		//if user was directed from trying to add something to cart
		if ($_SESSION['addToCart']){
			echo '<script> if(window.confirm("Welcome ' . $login[1]['fname'] . '! Added product to cart!")){window.location.href=\'products.php?submit=' . $_SESSION['addToCart_prod_id'].  '\';}; </script>';
				
		}
			
		echo '<script> if(window.confirm("Welcome ' . $login[1]['fname'] . '!")){window.location.href=\'index.php\';}; </script>';

	} else {
		//login failed [1] is an array of errors

		echo errorHandler($login[1]);
	}
}
?>
</body>
<?php include 'footer.php';?>
</html>
