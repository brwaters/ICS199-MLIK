<!doctype html>
<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css.css">
        <?php $page = 'search';
	include 'functions.php';
        include 'navbar.php'; ?>
        <?php
        //Setting up connection to database
        $connection = getConnection();

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        //variables
        $search = escapeString($_POST['search']);
        ?>
        <title>Products - MLIK</title>
    </head>


    <body>
	<div class='search'>
		<form class='search_bar' action="search.php" method='POST'> 
		    <p class='search_bar'> Search: </p> 
		    <input type='text' name='search'>
		</form>
	</div>

        <div id = "item_grid">

            <?php
            // Displaying products based on selection of category
            // 
            // If the the user made a selection for the category 
            // the $categories variable will be set. Otherwise we 
            // select everything

            if (isset($search)) {

                //We are only looking at the category that they selected
                $queryStr = "SELECT * FROM PRODUCTS WHERE Name LIKE '%" . $search . "%'";
                $query = $connection->query($queryStr);
            } else {

                //Here we select everything	
                $query = $connection->query("SELECT * FROM PRODUCTS");
            }
            //Here is a div generating loop, for each row that was returned in the query from earlier
            while ($dataCat = $query->fetch_assoc()) {
                ?>
                    <div class='product'>

                        <!-- subtle but important break -->
                        <br>

                        <!-- Here we retrieve the image based on the product id. The product with a product id of 1 will retrieve 1.jpg from the product_pics directory -->
                        <a href='product_info.php?product_id=<?php echo $dataCat['prod_id']; ?>'>
                        <img class='prod_img' src='product_pics/<?php echo $dataCat['prod_id']; ?>.jpg' alt=<?php echo $dataCat['Name']; ?> >	
                        </a>
                        <p class='prod_txt'><b><?php print $dataCat['Name']; ?></b></p> 
                        <p class='prod_txt'>$ <?php print $dataCat['Price']; ?></p>

                	<form action="products.php" method="GET" enctype="multipart/form-data">
                        	<button class='prod_txt' type="Submit" name="submit" value="<?php echo $dataCat['prod_id']; ?>">Add to cart</button>
               		</form>
			<br/>
                    </div>

                <?php
                //Closing the while loop from before. Yes this is wierd.
            }
            $connection->close()
            ?>
            <?php
            $user_id = $_SESSION['cust_id'];
            $product_id = $_GET['submit'];
	
            //echo 'Product ID:' . $product_id;

            if (isset($product_id)) { //Once pages is reloaded by clicking add cart, a product value is passed back to this page
		 if (!$_SESSION['loggedIn']) {
                    //handing product to add to session
                    $_SESSION['addToCart'] = true;
                    $_SESSION['addToCart_prod_id'] = $product_id;

                    //customer is not logged in, we display a message and redirect them to the login page
                    echo '
                        <script>
                            if (window.confirm(\'You are not logged in\')){
                                window.location.href=\'login.php\';
                            } else {
                                window.location.href=\'login.php\';
                            }
                        </script>
                        ';
                } else {

                $connection = getConnection();

                if ($connection->connect_error) { //Connection to DB opened
                    die("Connection failed: " . $connection->connect_error);
                }

                $addToCart = "INSERT INTO ICS199Group07_dev.CART(quantity,cust_id,prod_id) VALUES(1," . $user_id . "," . $product_id . ");";
                $incrementQuantity = "UPDATE ICS199Group07_dev.CART SET quantity = quantity + 1 WHERE cust_id = " . $user_id . " AND prod_id = " . $product_id . ";";
                $checkProdExists = "SELECT prod_id FROM ICS199Group07_dev.CART WHERE cust_id = " . $user_id . " AND prod_id = " . $product_id . ";";

                $prodExists = $connection->query($checkProdExists);
                //print_r($_SESSION);
                //echo $prodExists->num_rows;
                if (($prodExists->num_rows) == 1) {
                    //echo "There is an entry already, incrementing by 1:";
                    if ($connection->query($incrementQuantity) === TRUE) {
                        //echo "Incremented cart value";
                    } else {
                        //echo "Error: " . sql . "<br>" . $connection->error;
                        $connection->close();
                    }
                } else {
                    //echo "No entry exists.";
                    if ($connection->query($addToCart) === TRUE) {
                        //echo "Added to cart.";
                    } else {
                        //echo "Error: " . sql . "<br>" . $connection->error;
                        $connection->close();
                    }
                }
            }
            mysqli_close($connection);
	}
            ?>

        </ul>
    </div>
    <br>
    <br>
</body>


<?php include 'footer.php'; ?>

</html>
