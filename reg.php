<?php
session_start();
include('db.php');
if (count($_POST) > 0) 
{
	function clean($value = "") 
	{
	    $value = trim($value);
	    $value = stripslashes($value);
	    $value = strip_tags($value);
	    $value = htmlspecialchars($value, ENT_QUOTES);
	  
	    return $value;
	}
	$name = clean($_POST['name']);
	$email = clean($_POST['email']);
	$password = clean($_POST['password']);
	$password1 = clean($_POST['password1']);
	$query = mysqli_query($connection, "SELECT `email` FROM `user` WHERE `email` = '$email';");
    $checkemail = mysqli_fetch_assoc($query);

	if (empty($name) || empty($email) || empty($password) || empty($password1)) 
	{
		$error = 'Заполните все поля!';
		echo json_encode(array(
        'result'    => 'success',
        'error'      => 	$error
    	));
	}
	elseif($password != $password1)
	{
		$error = 'Ошибка при подтверждении пароля!';
		echo json_encode(array(
        'result'    => 'success',
        'error'      => 	$error
    	));
	}
	elseif (strlen($email) < 6 || !preg_match('/[A-Z]/i', $email) || !preg_match('/[@]/', $email) || strlen($email) > 40) 
	{
		$error = 'Email введен не коректно';
		echo json_encode(array(
        'result'    => 'success',
        'error'      => 	$error
    	));
	}
	elseif (strlen($password) < 6 || strlen($password) > 30)
	{
		$error = 'Некоректная длина пароля';
		echo json_encode(array(
        'result'    => 'success',
        'error'      => 	$error
    	));
	}
	elseif(strlen($name) < 2 || strlen($name) > 20)
	{
		$error = 'Некоректная длина имени';
		echo json_encode(array(
        'result'    => 'success',
        'error'      => 	$error
    	));
	}
	elseif ($checkemail['email'] != NULL) 
	{
		$error = 'Этот email уже зарегистрирован';
		echo json_encode(array(
        'result'    => 'success',
        'error'      => 	$error
    	));
	}
	else
	{
		$password = password_hash($password, PASSWORD_DEFAULT);
		mysqli_query($connection, "INSERT INTO `user` (`name`, `password`, `email`, `usergroup`) VALUES ('$name', '$password', '$email', '0');");
		$query = mysqli_query($connection, "SELECT `password`, `id` FROM `user` WHERE `email` = '$email';");
    	$userid = mysqli_fetch_assoc($query);
		$id = $userid['id'];
		$password = $userid['password'];
		$_SESSION['user'] = ['email' => $email, 'name' => $name, 'id' => $id, 'usergroup' => 0];
		setcookie('user_email',$email, time() +99999999);
		setcookie('user_pass',$password, time() +99999999);
		$noerror =
		"<script type='text/javascript'>
	    window.location = 'index.php';
	    </script>";
	    echo json_encode(array(
        'result'    => 'success',
        'error'      => 	$noerror
    	));
		die;
	}
} ?>