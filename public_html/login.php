<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="./css.css">
<title>home</title>
			<?php $page="login"; include 'functions.php'; include 'navbar.php'; ?>
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
	$privacy = false;

	if ($login[0]){
		$custID = $login[1]['cust_id'];
		//login successful [1] is the name
		
		//is priavcy policy accepted
		if(checkPolicy($custID)){
			$privacy = true;
		}
		else{
			
			$policy = false;
			?>
			<script>
			var r = confirm('Do you accept our privacy policy');
			if (r == true) {
				<?php $policy =true;?>
			}
			</script>
			<?php
			
			if($policy === true){
				echo "set policy";
				$privacy = true;
			}
		}
		
		if($privacy){
			//setting session variables
			$_SESSION['loggedIn'] = true;
			$_SESSION['cust_id'] = $custID;
			$_SESSION['fname'] = $login[1]['fname'];
			$_SESSION['account_type'] = $login[1]['account_type'];

			//if user was directed from trying to add something to cart
			if ($_SESSION['addToCart']){
				echo '<script> if(window.confirm("Welcome ' . $login[1]['fname'] . '! Added product to cart!")){window.location.href=\'products.php?submit=' . $_SESSION['addToCart_prod_id'].  '\';}; </script>';
					
			}
				
			echo '<script> if(window.confirm("Welcome ' . $login[1]['fname'] . '!")){window.location.href=\'index.php\';}; </script>';
		}else{
			echo errorHandler("Unable to log in until privacy agreement is set");
		}
	} else {
		//login failed [1] is an array of errors

		echo errorHandler($login[1]);
	}
}
?>
</body>
<?php include 'footer.php';?>
</html>
