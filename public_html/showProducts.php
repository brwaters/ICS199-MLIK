<html>
<head>
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
<form action="showProducts.php" method='POST'> 
<p> Category: </p> 
<select name='category'>
<?php  //category info
$query =$connection->query("SELECT * FROM CATEGORIES");
while ($dataCat = $query->fetch_assoc()){
$name = $dataCat['cat_name']?>

<option value="<?php echo $name;?>"><?php echo $name;?></option>
<?php } ?>

</select>
<input type='submit'>
</form>

<hr>

<?php
	//Displaying products based on selection of category

	if (isset($categories)){
		$queryStr = "SELECT p.prod_id, p.Name, p.Price FROM PRODUCTS p, CATEGORIES c, PRODUCT_CATEGORY pc WHERE c.cat_name = '" . $categories . "' AND p.prod_id = pc.PRODUCTS_prod_id AND c.cat_id = pc.CATEGORIES_cat_id;";
		$query = $connection->query($queryStr);
	} else {
		$query = $connection->query("SELECT * FROM PRODUCTS");
	}

	while($dataCat = $query->fetch_assoc()){ 
		?>
		<div class='product'>
<br>
		<img class='prod_img' src='product_pics/<?php echo $dataCat['prod_id'];?>.jpg' alt=<?php echo $dataCat['Name'];?> >	

		<p class='prod_txt'><b><?php print $dataCat['Name'];?></b></p> 
		<p class='prod_txt'>$ <?php print $dataCat['Price'];?></p>
		</div>
		<?php		
		
		}
		$connection ->close()?>

</body>
</html>
