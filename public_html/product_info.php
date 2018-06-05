<!doctype html>
<html>

<!--  to-do: add in description -->
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css.css">

        <title>Product - MLIK</title>
        <?php $page="product_info"; include 'navbar.php'; ?>
        <?php
        //session_start();	//start session
        //echo session_id();	//debug session

        $product_id = $_GET['product_id']; //get product id from last page

        $connection = new mysqli("localhost", "cst170", "381953", "ICS199Group07_dev"); // Connecting to database

        if ($connection->connect_error) { //show error if database connection fails
            die("Connection failed: " . $connection->connect_error);
        }
        ?>
    </head>

    <body>
        <p> Product: </p>
        <div class='product'>

            <!-- subtle but important break -->
            <?php
            $queryStr = 'SELECT * FROM PRODUCTS WHERE prod_id = $product_id;';
            $query[] = $connection->query($queryStr);
            $name = $query['Name'];
            $price = $query['Price'];
            $description = $query['Description'];
            
            mysqli_close($connection);
            ?>	

            <!-- Here we retrieve the image based on the product id. The product with a product id of 1 will retrieve 1.jpg from the product_pics directory -->
            <img class='prod_img' src='product_pics/<?php echo $product_id ?>.jpg'>
            <p class='prod_txt'>Name: <?php print $name; ?></p> 
            <p class='prod_txt'>Price: <?php print $price; ?></p>
            <p class='prod_txt'>Description: <?php print $description; ?></p>
            
        </div>

    </body>
    <?php include 'footer.php'; ?>
</html>
