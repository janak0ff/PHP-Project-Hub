<?php
session_start();
include_once "db.php";

// Include the PHPMailer library
require_once "PHPMailer/src/PHPMailer.php";
require_once "PHPMailer/src/SMTP.php";
require_once "PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Get user input data from the registration form
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$cpassword = password_hash($_POST['cpass'], PASSWORD_DEFAULT);
$Role = 'user';
$verification_status = '0';

// Check if all input fields are filled in
if (!empty($fname) && !empty($lname) && !empty($email) && !empty($phone) && !empty($password) && !empty($cpassword)) {
    // Check if the email address is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if the email address already exists in the database
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo htmlspecialchars("$email - Already Exists!");
        } else {
            if (password_verify($_POST['pass'], $password) && password_verify($_POST['cpass'], $cpassword)) {
                // Check if the user uploaded a profile picture
                if (isset($_FILES['image'])) {
                    // If file is uploaded, get the image information
                    $img_name = $_FILES['image']['name'];
                    $img_typ = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];
                    $img_explode = explode('.', $img_name);
                    $img_extension = end($img_explode);
                    $extensions = ['png', 'jpeg', 'jpg'];

                    // Check if the image extension is valid
                    if (in_array($img_extension, $extensions) === true) {
                        $time = time();
                        $newimagename = $time . $img_name;
                        // Move the uploaded image to the server
                        if (move_uploaded_file($tmp_name, "../Images/" . $newimagename)) {
                            $random_id = rand(time(), 10000000);
                            $otp = mt_rand(0000, 9999);

                            // Insert user data into the database
                            $stmt = $conn->prepare("INSERT INTO users (unique_id, fname, lname, email, phone, password, image, otp, verification_status, Role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("isssssssss", $random_id, $fname, $lname, $email, $phone, $password, $newimagename, $otp, $verification_status, $Role);
                            if ($stmt->execute()) {
                                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                                $stmt->bind_param("s", $email);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $_SESSION['unique_id'] = $row['unique_id'];
                                    $_SESSION['email'] = $row['email'];
                                    $_SESSION['otp'] = $row['otp'];

                                    // Send an email to the user with the OTP
                                    $mail = new PHPMailer();
                                    $mail->isSMTP();
                                    $mail->Host = 'smtp.gmail.com';
                                    $mail->SMTPAuth = true;
                                    $mail->Username = 'your mail address@gmail.com';
                                    $mail->Password = 'password';
                                    $mail->SMTPSecure = 'tls';
                                    $mail->Port = 587;

                                    $mail->setFrom('from@example.com', 'janak0ff');
                                    $mail->addAddress($email, $fname . ' ' . $lname);
                                    $mail->isHTML(true);
                                    $mail->Subject = 'Registration Confirmation';
                                    $mail->Body = 'Dear ' . $fname . ' ' . $lname . ',<br><br>Please confirm your registration by entering the OTP: ' . $otp;

                                    if ($mail->send()) {
                                        echo 'success';
                                    } else {
                                        echo 'Email could not be sent.';
                                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                                    }
                                }
                            } else {
                                echo "Somethings went wrong! " . mysqli_error($conn);
                            }
                        }
                    } else {
                        echo htmlspecialchars("$img_extension ~ This file extension is not allowed!");
                    }
                } else {
                    echo "Please Select an Profile File";
                }
            } else {
                echo htmlspecialchars("Passwords do not match!");
            }
        }
    } else {
        echo htmlspecialchars("$email ~ This is not a valid email!");
    }
} else {
    echo htmlspecialchars("All fields are required!");
}
