<?php 

session_start();

function signup($data)
{
	$errors = array();
 
	//validate 
	if(!preg_match('/^[a-zA-Z]+$/', $data['username'])){
		$errors[] = "Please enter a valid username";
	}

	if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
		$errors[] = "Please enter a valid email";
	}

	if(strlen(trim($data['password'])) < 4){
		$errors[] = "Password must be atleast 4 chars long";
	}

	if($data['password'] != $data['password2']){
		$errors[] = "Passwords must match";
	}

	$check = database_run("select * from users where email = :email limit 1",['email'=>$data['email']]);
	if(is_array($check)){
		$errors[] = "That email already exists";
	}

	//save
	if(count($errors) == 0){

		$arr['username'] = $data['username'];
		$arr['email'] = $data['email'];
		$arr['password'] = hash('sha256',$data['password']);
		$arr['date'] = date("Y-m-d H:i:s");

		$query = "insert into users (username,email,password,date) values 
		(:username,:email,:password,:date)";

		database_run($query,$arr);
	}
	return $errors;
}

function login($data)
{
	$errors = array();
 
	//validate 
	if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
		$errors[] = "Please enter a valid email";
	}

	if(strlen(trim($data['password'])) < 4){
		$errors[] = "Password must be atleast 4 chars long";
	}
 
	//check
	if(count($errors) == 0){

		$arr['email'] = $data['email'];
		$password = hash('sha256', $data['password']);

		$query = "select * from users where email = :email limit 1";

		$row = database_run($query,$arr);

		if(is_array($row)){
			$row = $row[0];

			if($password === $row->password){
				
				$_SESSION['USER'] = $row;
				$_SESSION['LOGGED_IN'] = true;
			}else{
				$errors[] = "wrong email or password";
			}

		}else{
			$errors[] = "wrong email or password";
		}
	}
	return $errors;
}

function database_run($query,$vars = array())
{
	$string = "mysql:host=localhost;dbname=verify_db";
	$con = new PDO($string,'root','');

	if(!$con){
		return false;
	}

	$stm = $con->prepare($query);
	$check = $stm->execute($vars);

	if($check){
		
		$data = $stm->fetchAll(PDO::FETCH_OBJ);
		
		if(count($data) > 0){
			return $data;
		}
	}

	return false;
}

function check_login($redirect = true){

	if(isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])){

		return true;
	}

	if($redirect){
		header("Location: login.php");
		die;
	}else{
		return false;
	}
	
}

function check_verified(){

	$id = $_SESSION['USER']->id;
	$query = "select * from users where id = '$id' limit 1";
	$row = database_run($query);

	if(is_array($row)){
		$row = $row[0];

		if($row->email == $row->email_verified){

			return true;
		}
	}
 
	return false;
 	
}
?>

//////////////////////////////////new////////////////////////////////////////////


<?php

session_start();

function signup($data)
{
	$errors = array();

	//validate 
	if (!preg_match('/^[a-zA-Z\d]+$/', $data['username'])) {
		$errors['username'] = "Please enter a valid username";
	}

	if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Please enter a valid email address";
	}

	if (strlen(trim($data['password'])) < 8) {
		$errors['password'] = "Password must be at least 8 characters long";
	}

	if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\s]).{8,}$/', $data['password'])) {
		$errors['password'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
	}

	if ($data['password'] != $data['password2']) {
		$errors['password'] = "Passwords must match";
	}

	$check = database_run("select * from users where email = :email limit 1", ['email' => $data['email']]);
	if (is_array($check)) {
		$errors['email'] = "That email already exists";
	}

	$check = database_run("select * from users where username = :username limit 1", ['username' => $data['username']]);
	if (is_array($check)) {
		$errors['username'] = "That username already exists";
	}

	//save
	if (count($errors) == 0) {

		$arr['username'] = $data['username'];
		$arr['email'] = $data['email'];
		$arr['password'] = hash('sha256', $data['password']);
		$arr['date'] = date("Y-m-d H:i:s");

		$query = "insert into users (username,email,password,date) values 
		(:username,:email,:password,:date)";

		database_run($query, $arr);
	}
	return $errors;
}
function login($data)
{
	$errors = array();

	if (isset($data['remember'])) {
		setcookie('usernameemail', $data['usernameemail'], time() + 60 * 60 * 24 * 30, '/');
		setcookie('password', $data['password'], time() + 60 * 60 * 24 * 30, '/');
	}

	// if (isset($data['remember'])) {
	// 	$usernameemail = $data['usernameemail'];
	// 	$password = hash('sha256', $data['password']);
	// 	setcookie('usernameemail', $usernameemail, time() + 60 * 60 * 24 * 30, '/');
	// 	setcookie('password', $password, time() + 60 * 60 * 24 * 30, '/');
	// }

	//check
	if (count($errors) == 0) {
		$usernameemail = $data['usernameemail'];
		$password = hash('sha256', $data['password']);

		$query = "SELECT * FROM users WHERE (username = '$usernameemail' OR email = '$usernameemail') AND password = '$password' LIMIT 1";

		$row = database_run($query);

		if (is_array($row) && count($row) > 0) {
			$row = $row[0];

			$_SESSION['USER'] = $row;
			$_SESSION['LOGGED_IN'] = true;
			return array('success' => true, 'username' => $row->username); // or any other user data you want to return
		} else {
			$query = "SELECT * FROM users WHERE username = '$usernameemail' OR email = '$usernameemail' LIMIT 1";
			$row = database_run($query);

			if (is_array($row) && count($row) > 0) {
				$errors['password'] = "Wrong password";
			} else {
				$errors['usernameemail'] = "You are not registered";
			}
		}
	}

	return array('success' => false, 'errors' => $errors);
}

function database_run($query, $vars = array())
{
	$string = "mysql:host=localhost; dbname=verify_db";
	$con = new PDO($string, 'root', '');

	if (!$con) {
		return false;
	}

	$stm = $con->prepare($query);
	$check = $stm->execute($vars);

	if ($check) {

		$data = $stm->fetchAll(PDO::FETCH_OBJ);

		if (count($data) > 0) {
			return $data;
		}
	}

	return false;
}

function check_login($redirect = true)
{

	if (isset($_SESSION['USER']) && isset($_SESSION['LOGGED_IN'])) {

		return true;
	}

	if ($redirect) {
		header("Location: login.php");
		die;
	} else {
		return false;
	}
}

function check_verified()
{

	$id = $_SESSION['USER']->id;
	$query = "select * from users where id = '$id' limit 1";
	$row = database_run($query);

	if (is_array($row)) {
		$row = $row[0];

		if ($row->email == $row->email_verified) {

			return true;
		}
	}

	return false;
}
?>