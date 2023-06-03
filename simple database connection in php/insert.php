
<?php
// $conn = mysqli_connect("localhost", "root", "", "my_db");

// // Check connection
// if (!$conn) {
// 	die('Could not connect to database: ' . mysqli_connect_error());
// }


// $sql = "INSERT INTO Persons (FirstName, LastName, Age)
// 	 VALUES ('$_POST[firstname]','$_POST[lastname]','$_POST[age]')";

// if (mysqli_query($conn, $sql)) {
// 	// echo "New record created successfully !";
// 	// Redirect to index.php
// 	header("Location: index.php");
// 	exit();
// } else {
// 	echo "Error: " . $sql . " " . mysqli_error($conn);
// }

// mysqli_close($conn);
?>



<?php
/*
// Connect to database
$conn = mysqli_connect("localhost", "root", "", "my_db");
if (!$conn) {
	die('Could not connect to database: ' . mysqli_connect_error());
}

// Prepare SQL statement
$sql = "INSERT INTO Persons (FirstName, LastName, Age) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
	die('Error preparing SQL statement: ' . mysqli_error($conn));
}

// Bind parameters and execute query
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$age = $_POST['age'];
mysqli_stmt_bind_param($stmt, 'ssi', $firstname, $lastname, $age);
if (mysqli_stmt_execute($stmt)) {
	echo "New record created successfully!";
} else {
	echo "Error inserting record: " . mysqli_error($conn);
}

// Close database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
*/
?>