<?php
session_start();
include('db.php');
if (!empty($_POST['password']) && !empty($_POST['email'])) 
{
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$query = mysqli_query($connection, "SELECT `password` FROM `user` WHERE `email` = '$email';");
	$checkpassword = mysqli_fetch_assoc($query);
	if ($checkpassword['password'] == NULL) 
	{
		$error = 'Email не зарегистрирован!';
		echo json_encode(array(
        'result'    => 'success',
        'error'      => 	$error
    	));
	}
	elseif (password_verify($password,$checkpassword['password']) == false)
	{
		$error = 'Пароль введен не коректно!';
		echo json_encode(array(
        'result'    => 'success',
        'error'      => 	$error
    	));
	}
	else
	{
		$query = mysqli_query($connection, "SELECT `name`, `id`, `usergroup` FROM `user` WHERE `email` = '$email';");
    	$sessdata = mysqli_fetch_assoc($query);
		$usergroup = $sessdata['usergroup'];
    	if ($usergroup == 2) 
    	{
    		$error = 'Вы забанены!';
    		echo json_encode(array(
	        'result'    => 'success',
	        'error'      => $error
	        ));
    	}
    	else
    	{
    		$password = $checkpassword['password'];
			$name = $sessdata['name'];
			$id = $sessdata['id'];
			$_SESSION['user'] = ['email' => $email, 'name' => $name, 'id' => $id, 'usergroup' => $usergroup];
			if (!empty($_POST['remember'])) 
			{
				setcookie('user_email',$email, time() +99999999);
				setcookie('user_pass',$password, time() +99999999);
			}
			$noerror =
			"<script type='text/javascript'>
		    window.location = 'index.php';
		    </script>";
		    echo json_encode(array(
	        'result'    => 'success',
	        'error'      => $noerror
	        ));
			die;
		}
	}
}
else
{
	$error = 'Заполните все поля!';
	echo json_encode(array(
    'result'    => 'success',
    'error'      => $error
    ));
} 
?>