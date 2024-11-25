<!DOCTYPE html>
 <html>

 <head>
        <title>Insert Data</title>
 </head>

 <body>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = mysqli_connect("localhost", "root", "", "bookstore");

            // Check connection
            if (!$conn) {
                die('Could not connect to database: ' . mysqli_connect_error());
            }

            $sql = "INSERT INTO set_a (bookName, sem, price, code)
	 VALUES ('$_POST[bookName]','$_POST[sem]','$_POST[price]','$_POST[code]')";

            if (mysqli_query($conn, $sql)) {
                // echo "New record created successfully !";
                // Redirect to index.php
                header("Location: book.php");
                exit();
            } else {
                echo "Error: " . $sql . " " . mysqli_error($conn);
            }

            mysqli_close($conn);
        } ?>

        <h1>Insert Data</h1>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

               <label>Book Name: <input type="text" name="bookName" placeholder="bookName"></label>
               <br>

               <label>sem: <input type="text" name="sem" placeholder="sem"></label>
               <br>

               <label>Price:<input type="number" name="price" placeholder="Price"></label>
               <br>

               <label>code:<input type="text" name="code" placeholder="code"></label>
               <br>

               <input type="submit" value="Submit">
        </form>

 </body>

 </html>