<!doctype html>
<html>
	<head>
		<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="./css.css">
			<?php
			//Setting up connection to database
			$connection = new mysqli("localhost", "cst170","381953","ICS199Group07_dev");

			if($connection -> connect_error){
				die("Connection failed: ". $connection ->connect_error);
			}

			//variables
			$categories = $_POST['category'];
			?>
		<title>Products - MLIK</title>
			<header>
				<h1>The mlik header image goes here!</h1>
				<ul id = "navbar">
					<li><a href="index.php">Home</a></li>
					<li>
						<div class="dropdown">
						  <button class="dropbtn">Dropdown</button>
						  <div class="dropdown-content">
							<?php  //category info

							//getting all categories
							$query =$connection->query("SELECT * FROM CATEGORIES");
						
							//Looping for each category in database
							while ($dataCat = $query->fetch_assoc()){
								
								$name = $dataCat['cat_name']; ?>

								<option value="<?php echo $name;?>"><?php echo $name;?></option>
						<?php } ?>
						  </div>
						</div>
					</li>
					<li><a href="cart.php">Cart</a></li>
				</ul>
			</header>
	</head>


	<body>
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

		<div id = "item_grid">

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
						<ul class = "searched_items">
							//Here is a div generating loop, for each row that was returned in the query from earlier
							while($dataCat = $query->fetch_assoc()){ ?>
								<li class = "item">
									<div class = 'product'> <!-- tile holds all of the item info together -->
										<div class = "item_photo"> 
													<!-- Here we retrieve the image based on the product id. The product with a product id of 1 will retrieve 1.jpg from the product_pics directory -->
											<img class='prod_img' src='product_pics/<?php echo $dataCat['prod_id'];?>.jpg' alt=<?php echo $dataCat['Name'];?> >	
											<p class = "item_info">
												<?php print $dataCat['Name'];?><br>
												$ <?php print $dataCat['Price'];?>
											</p>
										</div>
									</div>
								</li>
							<?php 
							//Closing the while loop from before. Yes this is wierd.
							} 
							$connection ->close()?>
						</ul>
		</div>

	</body>


	<footer>
	  <hr>
	  We sell quality goods! Check us out on Twitter, Social Media Site 1, Social Media Site 2
	</footer>
</html>
