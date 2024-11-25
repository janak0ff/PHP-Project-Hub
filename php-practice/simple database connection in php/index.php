<!DOCTYPE html>
 <html>

 <head>
        <title>Insert Data</title>
 </head>

 <body>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = mysqli_connect("localhost", "root", "", "my_db");

            // Check connection
            if (!$conn) {
                die('Could not connect to database: ' . mysqli_connect_error());
            }

            $sql = "INSERT INTO Persons (FirstName, LastName, Age)
	 VALUES ('$_POST[firstname]','$_POST[lastname]','$_POST[age]')";

            if (mysqli_query($conn, $sql)) {
                // echo "New record created successfully !";
                // Redirect to index.php
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $sql . " " . mysqli_error($conn);
            }

            mysqli_close($conn);
        } ?>

        <h1>Insert Data</h1>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
               <label>Firstname</label>
               <input type="text" name="firstname" placeholder="FirstName"><br>

               <label>Lastname</label>
               <input type="text" name="lastname" placeholder="LastName"><br>

               <label>Age</label>
               <input type="number" name="age" placeholder="Age"><br>

               <input type="submit" value="Submit">
        </form>

 </body>

 </html>