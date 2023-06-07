<!DOCTYPE html>
<html>

<head>
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
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
</head>

<body>
  <?php
  $string = "mysql:host=localhost; dbname=verify_db";
  $con = new PDO($string, 'root', '');

  $msg = ""; // Initialize error message variable

  if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['pwdrst'])) {
    echo "jana"
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conformPassword = $_POST['conformPassword'];
    if ($password == $conformPassword) {
      $stmt = $con->prepare("UPDATE users SET password=:password WHERE email_verified=:email");
      $stmt->bindValue(':password', $password);
      $stmt->bindValue(':email', $email);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $msg = 'Your password updated successfully <a href="index.php">Click here</a> to login';
      } else {
        $msg = "Error while updating password.";
      }
    } else {
      $msg = "Password and Confirm Password do not match";
    }
  }

  if (isset($_GET['secret'])) {
    $email_verified = base64_decode($_GET['secret']);
    $check_details = $con->prepare("SELECT email_verified FROM users WHERE email_verified=:email");
    $check_details->bindValue(':email', $email_verified);
    $check_details->execute();
    if ($check_details->rowCount() > 0) {
  ?>

      <div class="container">
        <div class="table-responsive">
          <h3 align="center">Reset Password</h3><br />
          <div class="box">
            <form id="validate_form" method="post">
              <input type="hidden" name="email" value="<?php echo $email_verified; ?>" />
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter Password" required data-parsley-type="password" data-parsley-trigger="keyup" class="form-control" />
              </div>
              <div class="form-group">
                <label for="conformPassword">Confirm Password</label>
                <input type="password" name="conformPassword" id="conformPassword" placeholder="Enter Confirm Password" required data-parsley-equalto="#password" data-parsley-trigger="keyup" class="form-control" />
              </div>
              <div class="form-group">
                <input type="submit" id="login" name="pwdrst" value="Reset Password" class="btn btn-success" />
              </div>

              <p class="error"><?php echo $msg; ?></p>
            </form>
          </div>
        </div>
      </div>
  <?php
    } else {
      echo "Invalid URL";
    }
  }