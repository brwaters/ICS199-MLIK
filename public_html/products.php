<!doctype html>
<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css.css">
        <style>
            .product {
                height:250px; 
                width:250px; 
                background-color:blue;  
                float: left; 
                /*position: relative;
                overflow: hidden;*/
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
                margin-right: 30px;
            }
        </style>
        <?php
        //Setting up connection to database
        $connection = new mysqli("localhost", "cst170", "381953", "ICS199Group07_dev");

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        //variables
        $categories = $_POST['category'];
        ?>
        <title>Products - MLIK</title>
        <?php $page = 'products';
        include 'navbar.php'; ?>
    </head>


    <body>

        <form action="products.php" method='POST'> 
            <p> Category: </p> 
            <select name='category'>

                <?php
                //category info
                //getting all categories
                $query = $connection->query("SELECT * FROM CATEGORIES");
                ?><option value="ALL">ALL</option> <?php
                //Looping for each category in database
                while ($dataCat = $query->fetch_assoc()) {

                    $name = $dataCat['cat_name'];
                    ?>

                    <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
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

            if (isset($categories) && $categories != "ALL") {

                //We are only looking at the category that they selected
                $queryStr = "SELECT p.prod_id, p.Name, p.Price FROM PRODUCTS p, CATEGORIES c, PRODUCT_CATEGORY pc WHERE c.cat_name = '" . $categories . "' AND p.prod_id = pc.PRODUCTS_prod_id AND c.cat_id = pc.CATEGORIES_cat_id;";
                $query = $connection->query($queryStr);
            } else {

                //Here we select everything	
                $query = $connection->query("SELECT * FROM PRODUCTS");
            }
            //Here is a div generating loop, for each row that was returned in the query from earlier
            while ($dataCat = $query->fetch_assoc()) {
                ?>
                <form action="products.php" method="POST" enctype="multipart/form-data">
                    <div class='product'>

                        <!-- subtle but important break -->
                        <br>

                        <!-- Here we retrieve the image based on the product id. The product with a product id of 1 will retrieve 1.jpg from the product_pics directory -->

                        <img class='prod_img' src='product_pics/<?php echo $dataCat['prod_id']; ?>.jpg' alt=<?php echo $dataCat['Name']; ?> >	

                        <p class='prod_txt'><b><?php print $dataCat['Name']; ?></b></p> 
                        <p class='prod_txt'>$ <?php print $dataCat['Price']; ?></p>
                        <button class='prod_txt' type="Submit" name="submit" value="<?php echo $dataCat['prod_id']; ?>">Add to cart</button>
                    </div>
                </form>

                <?php
                //Closing the while loop from before. Yes this is wierd.
            }
            $connection->close()
            ?>
            <?php
            $user_id = $_SESSION['cust_id'];
            $product_id = $_POST['submit'];
            //echo 'Product ID:' . $product_id;

            if (isset($product_id)) { //Once pages is reloaded by clicking add cart, a product value is passed back to this page
                $connection = new mysqli("localhost", "cst170", "381953", "ICS199Group07_dev");

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
            ?>

        </ul>
    </div>
    <br>
    <br>
</body>


<?php include 'footer.php'; ?>

</html>
