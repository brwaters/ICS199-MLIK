<html>
<head>

<?php
// =====================
// STYLES
// =====================
// ?>


<style>
	.product {
		height:250px; 
		width:250px; 
		background-color:blue;  
		float: left; 
		margin: 10px;
	}
	.prod_img {
		width:80%;
		height:60%; 
		margin-left: auto; 
		margin-right: auto;
	        display: block;
	}
	.prod_txt {
		margin-left: 30px;
	}
</style>





<?php
// =====================
// INITIAL CONNECTION
// To be placed in header
// =====================
// ?>

<?php
//Setting up connection to database
$connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev");

if($connection -> connect_error){
	die("Connection failed: ". $connection ->connect_error);
}

//variables
$categories = $_POST['category'];
?>






</head>
<h1>Products Page!</h1>
<body>







<?php
// =========================
// CATEGORY SELECTION  FORM 
// ========================
// ?>

<!-- This this will get the page to call ITSELF with the appropriate information -->
<form action="showProducts.php" method='POST'> 
<p> Category: </p> 
<select name='category'>

	<?php  //category info

		//getting all categories
		$query =$connection->query("SELECT * FROM CATEGORIES");
	
		//Looping for each category in database
		while ($dataCat = $query->fetch_assoc()){
			
			$name = $dataCat['cat_name']; ?>

			<option value="<?php echo $name;?>"><?php echo $name;?></option>
	<?php } ?>
</select>
<input type='submit'>
</form>








<hr>




<?php
// ================================================
// Displaying relevant products based on selection
// ================================================
// ?>

<?php
	// Displaying products based on selection of category
	// 
	// If the the user made a selection for the category 
	// the $categories variable will be set. Otherwise we 
	// select everything

	
	if (isset($categories)){

		//We are only looking at the category that they selected
		$queryStr = "SELECT p.prod_id, p.Name, p.Price FROM PRODUCTS p, CATEGORIES c, PRODUCT_CATEGORY pc WHERE c.cat_name = '" . $categories . "' AND p.prod_id = pc.PRODUCTS_prod_id AND c.cat_id = pc.CATEGORIES_cat_id;";
		$query = $connection->query($queryStr);

	} else {

		//Here we select everything	
		$query = $connection->query("SELECT * FROM PRODUCTS");
	}

	//Here is a div generating loop, for each row that was returned in the query from earlier
	while($dataCat = $query->fetch_assoc()){ ?>

		<div class='product'>
		
		<!-- subtle but important break --!>
		<br>
		
		<!-- Here we retrieve the image based on the product id. The product with a product id of 1 will retrieve 1.jpg from the product_pics directory -->
		<img class='prod_img' src='product_pics/<?php echo $dataCat['prod_id'];?>.jpg' alt=<?php echo $dataCat['Name'];?> >	

		<p class='prod_txt'><b><?php print $dataCat['Name'];?></b></p> 
		<p class='prod_txt'>$ <?php print $dataCat['Price'];?></p>
		</div>
	<?php 
	//Closing the while loop from before. Yes this is wierd.
	} 
	$connection ->close()?>











</body>
</html>
