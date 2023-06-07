<?php
require "mail.php";
require "functions.php";
check_login();

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == "GET" && !check_verified()) {

	//generate verification code
	$verification_code = rand(1000, 9999);

	//save to database
	$vars['expires'] = (time() + (60 * 10));
	$vars['email'] = $_SESSION['USER']->email;
	$vars['code'] = $verification_code;

	$query = "insert into verify (code,expires,email) values (:code,:expires,:email)";
	database_run($query, $vars);

	$user = $_SESSION['USER']->name ?? $_SESSION['USER']->username;

	$message =  'Dear ' . $user . ',<br><br>' . 'Your OTP code is: ' . $verification_code . ',<br><br>Please confirm your registration by clicking on the following link:<br><a href="http://localhost/login sys/verify.php?email=' . $vars['email'] . '&verification=' . $verification_code . '">http://localhost/login sys/verify.php?email=' . $vars['email'] . '&verification=' . $verification_code . '</a>';
	$email_subject = "Email verification";
	$email_recipient = $vars['email'];
	send_mail($email_recipient, $email_subject, $message);
}
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['email']) && isset($_GET['verification'])) {

	$email = $_GET['email'];
	$verification_code = $_GET['verification'];

	$query = "select * from verify where code = :code && email = :email";
	$vars = array();
	$vars['email'] = $email;
	$vars['code'] = $verification_code;

	$row = database_run($query, $vars);

	if (is_array($row)) {
		$row = $row[0];
		$time = time();

		if ($row->expires > $time) {

			$query = "update users set email_verified = email where email = :email limit 1";
			database_run($query, array('email' => $email));

			header("Location: profile.php");
			die;
		} else {
			$errors[] = "Code expired";
		}
	} else {
		$errors[] = "Wrong verification code";
	}
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['otp1']) && isset($_POST['otp2']) && isset($_POST['otp3']) && isset($_POST['otp4'])) {

	if (!check_verified()) {

		$otp = $_POST['otp1'] . $_POST['otp2'] . $_POST['otp3'] . $_POST['otp4'];

		$query = "select * from verify where code = :code && email = :email";
		$vars = array();
		$vars['email'] = $_SESSION['USER']->email;
		$vars['code'] = $otp;

		$row = database_run($query, $vars);

		if (is_array($row)) {
			$row = $row[0];
			$time = time();

			if ($row->expires > $time) {

				$id = $_SESSION['USER']->id;
				$query = "update users set email_verified = email where id = '$id' limit 1";

				database_run($query);

				header("Location: profile.php");
				die;
			} else {
				$errors[] = "Code expired";
			}
		} else {
			$errors[] = "Wrong verification code";
		}
	} else {
		$errors[] = "You're already verified";
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Verify</title>
</head>

</body>

<h1>Verify</h1>

<?php include('header.php'); ?>

<br><br>
<div>


	<!--<form method="post">
			<input type="text" name="code" placeholder="Enter your Code"><br>
 			<br>
			<input type="submit" value="Verify">
		</form> -->


	<div class="form" style="text-align: center;">
		<div style="color:red; font-weight:700;">
			<?php if (count($errors) > 0) : ?>
				<?php foreach ($errors as $error) : ?>
					<?= $error ?> <br>
				<?php endforeach; ?>
			<?php endif; ?>

		</div>
		<h2>Verify Your Account</h2>
		<p>We emailed you the four digit otp code to Enter the code below to confirm your email address.</p>
		<form method="post" autocomplete="off">
			<div class="error-text">Error</div>
			<div class="fields_input">
				<input type="number" name="otp1" class="otp_field" placeholder="0" min="0" max="9" required onpaste="return false">
				<input type="number" name="otp2" class="otp_field" placeholder="0" min="0" max="9" required onpaste="return false">
				<input type="number" name="otp3" class="otp_field" placeholder="0" min="0" max="9" required onpaste="return false">
				<input type="number" name="otp4" class="otp_field" placeholder="0" min="0" max="9" required onpaste="return false">
			</div>
			<div class="submit">
				<!-- <input type="button" name="resend" class="resend_btn" value="Resend Again"> -->
				<input type="submit" value="Verify" class="button">
			</div>
		</form>
	</div>
	<script>
		const otp = document.querySelectorAll('.otp_field');

		// Initially focus first input
		otp[0].focus();

		otp.forEach((field, index) => {
			field.addEventListener('keydown', (e) => {
				if (e.key >= 0 && e.key <= 9) {
					otp[index].value = "";
					setTimeout(() => {
						otp[index + 1].focus();
					}, 4);
				} else if (e.key === 'Backspace') {
					setTimeout(() => {
						otp[index - 1].focus();
					}, 4);
				}
			});
		});
	</script>


</div>

<style>
	@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap");

	* {
		/* margin: 0;
			padding: 0; */
		box-sizing: border-box;
		font-family: "Poppins", "sans-serif";
	}

	::selection {
		color: #ececec;
		background: #00b3ff;
	}

	.form {
		position: relative;
		background: #f2f3f7;
		width: 100%;
		max-width: 500px;
		margin: 0 auto;
		border-radius: 12px;
		box-shadow: -3px 3px 10px -5px rgba(0, 0, 0, 0.2);
		padding: 30px 30px;

	}

	.form h2 {
		font-size: 30px;
		font-weight: 700;
	}

	.form p {
		font-size: 14px;
		padding-bottom: 8px;
	}


	.form form {
		margin: 8px 0;
	}

	.form form .error-text {
		display: none;
		color: #851923;
		padding: 4px 6px;
		text-align: center;
		border-radius: 4px;
		background: #ffe3e5;
		border: 1px solid #dfa5ab;
		margin-bottom: 8px;
	}


	.form form input.button {
		height: 45px;
		border: none;
		color: #f2f3f7;
		width: 100%;
		font-size: 17px;
		background: #006692;
		border-radius: 5px;
		cursor: pointer;
		margin-top: 13px;
		border: 2px solid #2983aa;
	}




	/* verify account style start */
	.fields_input {
		display: flex;
		align-items: center;
		justify-content: center;
		margin: 15px 0;
	}

	.otp_field {
		border-radius: 5px;
		font-size: 60px;
		height: 100px;
		width: 100px;
		border: 3px solid #cacaca;
		margin: 1%;
		text-align: center;
		font-weight: 600;
		outline: none;
	}

	.otp_field::-webkit-inner-spin-button,
	.otp_field::-webkit-outer-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	.otp_field:valid {
		border-color: #3095d8;
		box-shadow: 0 10px 10px -5px rgba(0, 0, 0, 0.25);
	}

	.form form input.resend_btn {
		float: right;
		border: none;
		background: #f2f3f7;
		color: #006692;
		padding: 8px;
		font-size: 14px;
		border-radius: 5px;
		cursor: pointer;
		border: 2px solid #2983aa;
	}

	@media only screen and (max-width: 455px) {
		.otp_field {
			font-size: 55px;
			height: 80px;
			width: 80px;
		}
	}
</style>
</body>

</html>