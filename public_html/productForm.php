<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./css.css">
        <title>home</title>
        <?php include 'navbar.php'; ?>
    </head>
    <body>

        <form action="submitproduct.php" method="POST" enctype="multipart/form-data">
            <p> Name: <input type="text" name="name" value='' id='name'></p>
            <p> Description: <input type="text" name="description" ></p>
            <p> Price: <input type="text" name="price" ></p>
            <p> Image: <input type="file"  name="fileToUpload" id="fileToUpload"> </p>
            <p> Category: <select multiple name="category">
                    <?php
                    $connection = new mysqli("localhost", "cst170", "381953", "ICS199Group07_dev");

                    if ($connection->connect_error) {
                        die("Connection failed: " . $connection->connect_error);
                    }
                    $query = $connection->query("SELECT * FROM CATEGORIES");
                    while ($dataCat = $query->fetch_assoc()) {
                        print $dataCat["cat_name"];
                        echo "loop <br>";
                        echo "<option value=" . $dataCat["cat_id"] . ">" . $dataCat["cat_name"] . " </option>";
                    }
                    ?>
                </select></p>
            <input type="submit" value="submit" id="submit1" />
        </form>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="productForm.js"></script>

    </body>
</html>