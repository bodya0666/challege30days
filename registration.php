<?php 
session_start();
include('db.php');
if (!empty($_SESSION['user'])) 
{
	echo
	"<script type='text/javascript'>
    window.location = 'index.php';
    </script>";
}
include('apps/header.php');
$name = '';
$email = '';
$password = '';
$password1 = '';
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
		echo 'Заполните все поля!';
	}
	elseif($password != $password1)
	{
		echo 'Ошибка при подтверждении пароля!';
	}
	elseif (strlen($email) < 6 || !preg_match('/[A-Z]/i', $email) || !preg_match('/[@]/', $email) || strlen($email) > 40) 
	{
		echo 'Email введен не коректно';
	}
	elseif (strlen($password) < 6 || strlen($password) > 30)
	{
		echo 'Некоректная длина пароля';
	}
	elseif(strlen($name) < 2 || strlen($name) > 20)
	{
		echo 'Некоректная длина имени';
	}
	elseif ($checkemail['email'] != NULL) 
	{
		echo 'Этот email уже зарегистрирован';
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
		echo
		"<script type='text/javascript'>
	    window.location = 'index.php';
	    </script>";
		die;
	}
}
?>
<div class="center">
	<form method="POST">
		<label class="email" for="name">Ваше имя:</label>
		<input type="text" name="name" value="<?php echo $name; ?>" required>
		<label for="email">Email:</label>
		<input type="email" name="email" value="<?php echo $email; ?>" required>
		<label for="password">Пароль:</label> 
		<input type="password" name="password" value="<?php echo $password; ?>" required>
		<label for="password1">Подтвеждение пароля:</label>
		<input type="password" name="password1" value="<?php echo $password1; ?>" required>
		<input class="btn submit" type="submit" value="Подтвердить">
	</form>
</div>
<?php include('apps/footer.php');?>
