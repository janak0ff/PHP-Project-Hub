
<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = "my_db";
$conn = mysqli_connect($servername, $username, $password, "$dbname");
if (!$conn) {
	die('Could not Connect My Sql:' . mysql_error());
}

$sql = "INSERT INTO Persons (FirstName, LastName, Age)
	 VALUES ('$_POST[firstname]','$_POST[lastname]','$_POST[age]')";
if (mysqli_query($conn, $sql)) {
	echo "New record created successfully !";
} else {
	echo "Error: " . $sql . "
" . mysqli_error($conn);
}
mysqli_close($conn);
?>

