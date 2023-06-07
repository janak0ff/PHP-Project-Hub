<?php
$string = "mysql:host=localhost; dbname=verify_db";
$con = new PDO($string, 'root', '');

require "mail.php";
require "functions.php";

if (isset($_REQUEST['pwdrst'])) {
  $email = $_REQUEST['email'];

  $stmt = $con->prepare("SELECT email_verified, username FROM users WHERE email_verified = :email");
  $stmt->bindValue(':email', $email);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC)['username'];

  $res = $stmt->rowCount();

  if ($res > 0) {
    // generate verification code
    $verification_code = rand(1000, 9999);

    // save to database
    $vars['expires'] = (time() + (60 * 10));
    $vars['email'] = $email;
    $vars['code'] = $verification_code;

    $query = "INSERT INTO verify (code, expires, email) VALUES (:code, :expires, :email)";
    database_run($query, $vars);

    $message = '<div>
      <p><b>Dear ' . $user . ',</b></p>
      <p>You are receiving this email because we received a password reset request for your account.</p>
      <br>
      <p><button class="btn btn-primary"><a href="http://localhost/login sys/passwordreset.php?email=' . $vars['email'] . '&verification=' . $verification_code . '">Reset Password</a></button></p>
      <br>
      <p>If you did not request a password reset, no further action is required.</p>
    </div>';

    $email_subject = "Reset Password";
    $email_recipient = $email;

    send_mail($email_recipient, $email_subject, $message);
    $msg = "We have e-mailed your password reset link!";
  } else {
    $msg = "We can't find a user with that email address";
  }
}

?>

<html>

<head>
  <title>Forgot Password</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
</head>
<style>
  .box {
    width: 100%;
    max-width: 600px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 16px;
    margin: 0 auto;
  }

  input.parsley-success,
  select.parsley-success,
  textarea.parsley-success {
    color: #468847;
    background-color: #DFF0D8;
    border: 1px solid #D6E9C6;
  }

  input.parsley-error,
  select.parsley-error,
  textarea.parsley-error {
    color: #B94A48;
    background-color: #F2DEDE;
    border: 1px solid #EED3D7;
  }

  .parsley-errors-list {
    margin: 2px 0 3px;
    padding: 0;
    list-style-type: none;
    font-size: 0.9em;
    line-height: 0.9em;
    opacity: 0;

    transition: all .3s ease-in;
    -o-transition: all .3s ease-in;
    -moz-transition: all .3s ease-in;
    -webkit-transition: all .3s ease-in;
  }

  .parsley-errors-list.filled {
    opacity: 1;
  }

  .parsley-type,
  .parsley-required,
  .parsley-equalto {
    color: #ff0000;
  }

  .error {
    color: red;
    font-weight: 700;
  }
</style>

<body>
  <div class="container">
    <div class="table-responsive">
      <h3 align="center">Forgot Password</h3><br />
      <div class="box">
        <form id="validate_form" method="post">
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="text" name="email" id="email" placeholder="Enter Email" required data-parsley-type="email" data-parsley-trigger="keyup" class="form-control" />
          </div>
          <div class="form-group">
            <input type="submit" id="login" name="pwdrst" value="Send Password Reset Link" class="btn btn-success" />
          </div>

          <p class="error"><?php if (!empty($msg)) {
                              echo $msg;
                            } ?></p>
        </form>
      </div>
    </div>
  </div>
</body>

</html>