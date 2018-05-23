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
</head>


<h1>Products Page!</h1>

<body>
<hr>
<?php
	$connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev");
	
	if($connection -> connect_error){
		die("Connection failed: ". $connection ->connect_error);
	}
	$query =$connection->query("SELECT * FROM PRODUCTS");
	while($dataCat = $query->fetch_assoc()){ 
		?>
		<div class='product'>
<br>
		<img class='prod_img' src='product_pics/<?php echo $dataCat['prod_id'];?>.jpg' alt=<?php echo $dataCat['Name'];?> >	

		<p class='prod_txt'><b><?php print $dataCat['Name'];?></b></p> 
		<p class='prod_txt'>$ <?php print $dataCat['Price'];?></p>
		</div>
		<?php		
		
		}?>
<br>
<hr>
</body>
</html>
