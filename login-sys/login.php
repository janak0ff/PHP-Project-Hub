<?php

require "functions.php";
$usernameemail = filter_input(INPUT_POST, 'usernameemail', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$errors = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$result = login($_POST);
	$errors = $result['errors'];

	if ($result['success']) {
		header("Location: profile.php");
		die;
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Login</title>
</head>

<body align="center">
	<h1>Login</h1>

	<?php include('header.php') ?>

	<div>
		<div>
			<?php if (count($errors) > 0) : ?>
				<?php foreach ($errors as $error) : ?>
					<p><?= $error ?></p>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<form method="post">
			<input type="text" name="usernameemail" placeholder="Email or username" required value="<?php if (isset($_COOKIE['usernameemail'])) echo $_COOKIE['usernameemail']; ?>">
			<br>
			<input type="password" name="password" placeholder="Password" required value="<?php if (isset($_COOKIE['password'])) echo $_COOKIE['password']; ?>">
			<br>
			<input type="checkbox" name="remember" id="remember" <?php if (isset($_COOKIE['usernameemail']) && isset($_COOKIE['password'])) echo "checked"; ?>>
			<label for="remember">Remember me</label><br>
			<input type="submit" value="Login">
		</form>
		<p>Forgot your password? <a href="forgot.php">Reset it here</a>.</p>
	</div>
</body>

</html>