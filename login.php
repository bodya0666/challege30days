<?php 

session_start();
include('db.php');
include('apps/header.php');
if (!empty($_SESSION['user'])) 
{
	echo
	"<script type='text/javascript'>
    window.location = 'index.php';
    </script>";
}
$email = "";
$password = "";
if (!empty($_POST['password']) && !empty($_POST['email'])) 
{
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$query = mysqli_query($connection, "SELECT `password` FROM `user` WHERE `email` = '$email';");
	$checkpassword = mysqli_fetch_assoc($query);
	if ($checkpassword['password'] == NULL) 
	{
		echo 'Email не зарегистрирован!';
	}
	elseif (password_verify($password,$checkpassword['password']) == false)
	{
		echo 'Пароль введен не коректно!';
	}
	else
	{
		$query = mysqli_query($connection, "SELECT `name`, `id`, `usergroup` FROM `user` WHERE `email` = '$email';");
    	$sessdata = mysqli_fetch_assoc($query);
		$usergroup = $sessdata['usergroup'];
    	if ($usergroup == 2) 
    	{
    		echo 'Вы забанены!';
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
			echo
			"<script type='text/javascript'>
		    window.location = 'index.php';
		    </script>";
			die;
		}
	}
}
?>
	<div class="center">
		<form method="POST">
			<label class="email" for="email">Email:</label>
			<input type="email" name="email" value="<?php echo $email; ?>" required>
			<label for="password">Пароль:</label>
			<input type="password" name="password" value="<?php echo $password; ?>" required>
			<span><input style="margin: 0;" type="radio" name="remember" value="remember"> Запомнить меня</span>
			<input class="btn submit" type="submit" value="войти">
		</form>
	</div>
<?php include('apps/footer.php');?>
