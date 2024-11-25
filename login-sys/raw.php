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
    $subject = "Email verification";
    $recipient = $vars['email'];
    send_mail($recipient, $subject, $message);
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['otp1']) && isset($_POST['otp2']) && isset($_POST['otp3']) && isset($_POST['otp4'])) {

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
    $subject = "Email verification";
    $recipient = $vars['email'];
    send_mail($recipient, $subject, $message);
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
}
?>
