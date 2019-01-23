<?php 
session_start();
if (empty($_SESSION)) 
{
	if (!empty($_COOKIE['user_email']) && !empty($_COOKIE['user_pass'])) 
	{
		$email = $_COOKIE['user_email'];
		$password = $_COOKIE['user_pass'];
	 	$query = mysqli_query($connection, "SELECT `name`, `id`, `usergroup` FROM `user` WHERE `email` = '$email' and `password` = '$password' and 'usergroup' < 2;");
	 	$userdata = mysqli_fetch_assoc($query);
	 	$_SESSION['user'] = ['email' => $email, 'name' => $userdata['name'], 'id' => $userdata['id'], 'usergroup' => $userdata['usergroup']];
	}
}


include('db.php');
/* Проверка на роль пользователя */
if ($_SESSION['user']['usergroup'] != '1') 
{
	echo
	"<script type='text/javascript'>
    window.location = 'index.php';
    </script>";
}
$query = mysqli_query($connection, "SELECT `usergroup` FROM `user` WHERE `id` = {$_SESSION['user']['id']};");
$checkusergroup = mysqli_fetch_assoc($query);
if ($checkusergroup['usergroup'] != '1') 
{
	echo
	"<script type='text/javascript'>
    window.location = 'index.php';
    </script>";
}
if (empty($_SESSION['user']['usergroup']))
{
	echo
	"<script type='text/javascript'>
    window.location = 'login.php';
    </script>";
}
/* Проверка на роль пользователя */
function sqlarray($query)
{
	$result = [];
	while ($row = mysqli_fetch_array($query))
	{
    	$result[] = $row[0];
	}
	echo count($result);
}
function clean($value = "") 
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    
    return $value;
}
/* Статистика */
if (empty($_GET))
{
	$countcomment = mysqli_query($connection, "SELECT * FROM `comment`;");
	$countuser = mysqli_query($connection, "SELECT * FROM `user`;");
	$countcategory = mysqli_query($connection, "SELECT * FROM `category`;");
	$countpost = mysqli_query($connection, "SELECT * FROM `post`;");
	$counthidepost = mysqli_query($connection, "SELECT * FROM `post` WHERE `hide` = '1';");
	$countbaneduser = mysqli_query($connection, "SELECT * FROM `user` WHERE `usergroup` = '2';");
}/* Статистика */
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="stylesheet/style.css">
	<link rel="shortcut icon" href="blogimages/logo.jpg">
	<link href="stylesheet/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	<title>admin</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div class="wrapper">
	<div class="content">
<header>
<ul class="flex footer">
	<li><a href="admin.php">Admin panel</a></li>
	<li>
	<?php 
		if (empty($_SESSION['user'])) 
		{
			echo '<a href="login.php">Войти</a> | <a href="registration.php">Зарегистрироватся</a>';	
		}
		else
		{	
			$name = $_SESSION['user']['name'];
		 	echo '<li class=\"flex\">
		 	 <a href="index.php">Просмотр сайта</a> |' . 
					 " <div class=\"dropdown\">
					  <span class=\"dropbtn\">$name</span>
					  <div class=\"dropdown-content\">
					    <a href=\"mypost.php\">Мои посты</a>
					    <a href=\"logout.php\">Выйти</a>
					  </div>
					</div> 
				   </li>";		
		}
	?>	 	
	</li>
</ul>

</header>
<div class="between column">
	<!--Навигация-->
	<div class="category">
		<ul class="list">
			<h5>Навигация:</h5>
			<li>
				<a href="?mod=comment">Коментарии</a>
			</li>
			<li>
				<a href="?mod=user">Пользователи</a>
			</li>
			<li>
				<a href="?mod=category">Категории</a>
			</li>
			<li>
				<a href="?mod=post">Посты</a>
			</li>
		</ul>
	</div>
<?php 
if(empty($_GET))
{ /* Статистика по сайту. Начало: */
?>
	<div class="stats">
		<h3>Общая статистика сайта</h3>
		<table>
			<tr>
				<td>Количество комментариев</td>
				<td><?php sqlarray($countcomment); ?></td>
			</tr>
			<tr>
				<td>Количество забаненых пользователей</td>
				<td><?php sqlarray($countbaneduser); ?></td>
			</tr>
			<tr>
				<td>Количество пользователей</td>
				<td><?php sqlarray($countuser); ?></td>
			</tr>
			<tr>
				<td>Количество категорий</td>
				<td><?php sqlarray($countcategory); ?></td>
			</tr>
			<tr>
				<td>Количество постов</td>
				<td><?php sqlarray($countpost); ?></td>
			</tr>
			<tr>
				<td>Количество скрытых постов</td>
				<td><?php sqlarray($counthidepost); ?></td>
			</tr>
		</table>
	</div>
	<div></div>
<?php 
	/* Статистика по сайту. Конец. */
}  /* Удаление коментатия. Начало: */
elseif ($_GET['mod'] == 'comment')
{ 
	
	if (!empty($_GET['deletecomment']) && !empty($_GET['postid'])) 
	{
		if (is_numeric($_GET['deletecomment']) && is_numeric($_GET['postid']))
		{
			mysqli_query($connection, "DELETE FROM `comment` WHERE `id` = {$_GET['deletecomment']}");
			mysqli_query($connection, "UPDATE `post` SET `countcomment` = `countcomment` - 1 WHERE `id` = {$_GET['postid']};");
			echo
			"<script type='text/javascript'>
		    window.location = 'admin.php?mod=comment';
		    </script>";
		}
	}

	/* Удаление коментатия. Конец. */
	/* Поиск по коментариям. Начало: */
?>
	<div class="column width">
		<h3>Поиск:</h3>
		<form class="flex" style="margin: 0;" method="POST">
			<input style="width: 306px; margin: 10px 0;" name="searchcomment" type="text">
			<span><input style="margin: 0;" type="radio" name="coincidence" value="coincidence"> Точное совпадение</span>
			<select class="postform" name="searchcommentparam">
				<option>текст</option>
				<option>ид автора</option>
				<option>автор</option>
				<option>дата</option>
			</select>
			<input class="btn postform" type="submit" value="Поиск">
		</form>
<?php 	
		if (count($_POST) > 0) 
		{
			if (empty($_POST['coincidence']))
			{
				$searchcomment = '%' . $_POST['searchcomment'] . '%';
			}
			else
			{
				$searchcomment = $_POST['searchcomment'];
			}
			$searchcommentparam = $_POST['searchcommentparam'];
			$option = ['текст', 'ид автора', 'автор', 'дата'];
			$namecolumn = ['text', 'autorid', 'autor', 'date'];
			$searchcommentparam = str_replace($option, $namecolumn, $searchcommentparam);
			$query = mysqli_query($connection, "SELECT * FROM `comment` WHERE `$searchcommentparam` LIKE '$searchcomment' ORDER BY `comment`.`date` DESC;"); // Поиск по параметрам
		}
		else
		{
			$query = mysqli_query($connection, "SELECT * FROM `comment` ORDER BY `comment`.`date` DESC");
		}/* Поиск по коментариям. Конец. */
		 /* Вывод коментариев. Начало: */
		if (mysqli_num_rows($query) == 0)
		{
			echo 'Нечего не найдено!';
		}
		while ($comment = mysqli_fetch_assoc($query)) 
			{ 			
				$postid = $comment['postid'];
				$postid = mysqli_query($connection, "SELECT `title` FROM `post` WHERE `id` = '$postid';");
				$postid = mysqli_fetch_assoc($postid);	
				?>
		    <div class="comment">	      
		        <div style="padding-bottom: 10px;" class="between">
				    <div>
				    	Название поста: <a href="post.php?id=<?php echo $comment['postid']; ?>"><?php echo $postid['title']; ?></a>
				    </div>
				    <em class="date"> <?php echo $comment['date']; ?></em>
				    <span>
				    	<a href="admin.php?mod=comment&deletecomment= <?php echo $comment['id'] ?>&postid=<?php echo $comment['postid']; ?>">Удалить</a>
				    </span>
			    </div>
			    <span class="name">
			    	Автор: <?php echo $comment['autor']; ?> id: <?php echo $comment['autorid']; ?>
			    </span>
		        <div style="padding-left: 10px;">
		      		<?php echo $comment['text']; ?>
		  	    </div>	    
		    </div>
	  <?php } /* Вывод коментариев. Конец. */ ?>
	   </div>
	    <div></div>
<?php 
}
elseif ($_GET['mod'] == 'user')
{ 
	/* Удаление пользователя. Начало: */
	if (!empty($_GET['deleteuser'])) 
	{
		if (is_numeric($_GET['deleteuser']))
		{
			mysqli_query($connection, "DELETE FROM `user` WHERE `id` = {$_GET['deleteuser']}");
			echo
			"<script type='text/javascript'>
		    window.location = 'admin.php?mod=user';
		    </script>";
		}
	}
	/* Удаление пользователя. Конец. */
	/* Удаление всех постов пользователя. Начало: */
	if (!empty($_GET['deleteuserpost'])) 
	{
		if (is_numeric($_GET['deleteuserpost']))
		{
			mysqli_query($connection, "DELETE FROM `post` WHERE `autorid` = {$_GET['deleteuserpost']}");
			echo
			"<script type='text/javascript'>
		    window.location = 'admin.php?mod=user';
		    </script>";
		}
	}
	/* Удаление всех постов пользователя. Конец. */
	/* Удаление всех коментариев пользователя. Начало: */
	if (!empty($_GET['deleteusercomment'])) 
	{
		if (is_numeric($_GET['deleteusercomment']))
		{
			mysqli_query($connection, "DELETE FROM `comment` WHERE `autorid` = {$_GET['deleteusercomment']}");
			echo
			"<script type='text/javascript'>
		    window.location = 'aadmin.php?mod=user';
		    </script>";
		}
	}
	/* Удаление всех коментариев пользователя. Конец. */
	/* Изменение групы пользователя. Начало: */
	if (!empty($_GET['usergroup']) && isset($_GET['newgroup'])) 
	{
		if (is_numeric($_GET['usergroup']) && is_numeric($_GET['newgroup']))
		{
			mysqli_query($connection, "UPDATE `user` SET `usergroup` = {$_GET['newgroup']} WHERE `id` = {$_GET['usergroup']};");
			echo
			"<script type='text/javascript'>
		    window.location = 'admin.php?mod=user';
		    </script>";
		}
	}
	/* Изменение групы пользователя. Конец. */
	/* Создание нового польвателя. Начало: */
	if (!empty($_GET['createuser'])) 
	{
		if (is_numeric($_GET['createuser']))
		{ 
			echo '<div id="wrap_preloader">';
			$createname = '';
			$createemail = '';
			$createpassword = '';
			$createpassword1 = '';
			if (count($_POST) > 0) 
			{
				$createname = clean($_POST['name']);
				$createemail = clean($_POST['email']);
				$createpassword = clean($_POST['password']);
				$createpassword1 = clean($_POST['password1']);
				$query = mysqli_query($connection, "SELECT `email` FROM `user` WHERE `email` = '$createemail';");
			    $checkemail = mysqli_fetch_assoc($query);

				if (empty($createname) || empty($createemail) || empty($createpassword) || empty($createpassword1)) 
				{
					echo '<span class="alert">Заполните все поля!</span>';
				}
				elseif($createpassword != $createpassword1)
				{
					echo '<span class="alert">Ошибка при подтверждении пароля!</span>';
				}
				elseif (strlen($createemail) < 6 || !preg_match('/[A-Z]/i', $createemail) || !preg_match('/[@]/', $createemail) || strlen($createemail) > 40) 
				{
					echo '<span class="alert">Email введен не коректно</span>';
				}
				elseif (strlen($createpassword) < 6 || strlen($createpassword) > 30)
				{
					echo '<span class="alert">Некоректная длина пароля</span>';
				}
				elseif(strlen($createname) < 2 || strlen($createname) > 20)
				{
					echo '<span class="alert">Некоректная длина имени</span>';
				}
				elseif ($checkemail['email'] != NULL) 
				{
					echo '<span class="alert">Этот email уже зарегистрирован</span>';
				}
				else
				{
					if (!empty($_POST['role'])) 
					{
						$role = '1';
					}
					else
					{
						$role = '0';
					}
					$createpassword = password_hash($createpassword, PASSWORD_DEFAULT);
					mysqli_query($connection, "INSERT INTO `user` (`name`, `password`, `email`, `usergroup`) VALUES ('$createname', '$createpassword', '$createemail', '$role');");
					echo
					"<script type='text/javascript'>
				    window.location = 'admin.php?mod=user';
				    </script>";
					die;
				}
			}
		}
		?>
		
		<form class="createuser" method="POST">
			<label class="email" for="name">Имя:</label><a href="admin.php?mod=user"><i class="fas fa-times fa-3x"></i></a>
			<input type="text" name="name" value="<?php echo $createname; ?>" required>
			<label for="email">Email:</label>
			<input type="email" name="email" value="<?php echo $createemail; ?>" required>
			<label for="password">Пароль:</label> 
			<input type="password" name="password" value="<?php echo $createpassword; ?>" required>
			<label for="password1">Подтвеждение пароля:</label>
			<input type="password" name="password1" value="<?php echo $createpassword1; ?>" required>
			<span><input style="margin: 0;" type="radio" name="role" value="admin"> Сделать админом</span>
			<input class="submit" type="submit" value="Подтвердить">
		</form>
		</div>
		<?php 
	}	
	/* Создание нового польвателя. Конец. */
	/* Редактирование польвателя. Начало: */
	if (!empty($_GET['edituser'])) 
	{
		if (is_numeric($_GET['edituser']))
		{ 
			echo '<div id="wrap_preloader">';
			$userdata = mysqli_query($connection, "SELECT * FROM `user` WHERE `id` = {$_GET['edituser']};");
			$userdata = mysqli_fetch_assoc($userdata);
			$email = $userdata['email'];
			$name = $userdata['name'];
			if (count($_POST) > 0) 
			{
				$email = clean($_POST['email']);
				$name = clean($_POST['name']);
				$password = clean($_POST['password']);
				$query = mysqli_query($connection, "SELECT `email` FROM `user` WHERE `email` = '$email';");
	    		$checkemail = mysqli_fetch_assoc($query);
				if (empty($name) && empty($email)) 
				{
					echo '<span class="alert">Заполните поля: имя, email!</span>';
				}
				elseif(strlen($name) < 2 || strlen($name) > 20)
				{
					echo '<span class="alert">Некоректная длина имени</span>';
				}
				elseif (strlen($email) < 6 || !preg_match('/[A-Z]/i', $email) || !preg_match('/[@]/', $email) || strlen($email) > 40) 
				{
					echo '<span class="alert">Email введен не коректно</span>';
				}
				elseif ($userdata['email'] != $email) 
				{
					if ($checkemail['email'] != NULL) 
					{
						echo '<span class="alert">Этот email уже зарегистрирован</span>';
					}
					else
					{
						if (!empty($password)) 
						{
							if (strlen($password) < 6 || strlen($password) > 30)
							{
								echo '<span class="alert">Некоректная длина пароля</span>';
							}
							else
							{
								$password = password_hash($password, PASSWORD_DEFAULT);
								mysqli_query($connection, "UPDATE `user` SET `password` = '$password', `email` = '$email', `name` = '$name' WHERE `id` = {$userdata['id']};");
								echo
								"<script type='text/javascript'>
							    window.location = 'admin.php?mod=user';
							    </script>";
							}
						}
						else
						{
							mysqli_query($connection, "UPDATE `user` SET `email` = '$email', `name` = '$name' WHERE `id` = {$userdata['id']};");
							echo
							"<script type='text/javascript'>
						    window.location = 'admin.php?mod=user';
						    </script>";
						}
					}
				}
				else
				{
					if (!empty($password)) 
					{
						if (strlen($password) < 6 || strlen($password) > 30)
						{
							echo '<span class="alert">Некоректная длина пароля</span>';
						}
						else
						{
							$password = password_hash($password, PASSWORD_DEFAULT);
							mysqli_query($connection, "UPDATE `user` SET `password` = '$password', `email` = '$email', `name` = '$name' WHERE `id` = {$userdata['id']};");
							echo
							"<script type='text/javascript'>
						    window.location = 'admin.php?mod=user';
						    </script>";
						}
					}
					else
					{
						mysqli_query($connection, "UPDATE `user` SET `email` = '$email', `name` = '$name' WHERE `id` = {$userdata['id']};");
						echo
						"<script type='text/javascript'>
					    window.location = 'admin.php?mod=user';
					    </script>";
					}					
				}

			}
			 ?>
		
			<form class="edituser" method="POST">
				<label class="email" for="name">Имя:</label><a href="admin.php?mod=user"><i class="fas fa-times fa-3x"></i></a>
				<input type="text" name="name" value="<?php echo $name; ?>" >
				<label for="email">Email:</label>
				<input type="email" name="email" value="<?php echo $email; ?>" >
				<label for="password">Пароль:</label> 
				<input type="password" name="password">
				<input class="btn submit" type="submit" value="Изменить">
			</form></div>
		<?php }	
	}
	/* Редактирование польвателя. Конец. */	
	/* Поиск по пользователям. Начало: */
?>
			<div class="column width">
				<h3>Поиск:</h3>
		<form class="flex" style="margin: 0;" method="POST">
			<input class="shortinp" style="" name="searchuser" type="text">
			<span>
				<input style="margin: 0;" type="radio" name="coincidence" value="coincidence"> Точное совпадение
			</span>
			<select class="postform" name="searchuserparam">
				<option>имя</option>
				<option>ид</option>
				<option>email</option>
			</select>
			<input class="btn postform" type="submit" value="Поиск">
		</form>
		<a href="admin.php?mod=user&createuser=1"><button class="btn postform">Добавить пользотеля <i class="fas fa-plus"></i></button></a>
<?php 
		if (!empty($_POST['searchuser']))
		{	
			if (empty($_POST['coincidence']))
			{
				$searchuser = '%' . $_POST['searchuser'] . '%';
			}
			else
			{
				$searchuser = $_POST['searchuser'];
			}
			$searchuserparam = $_POST['searchuserparam'];
			$option = ['имя', 'ид', 'email'];
			$namecolumn = ['name', 'id', 'email'];
			$searchuserparam = str_replace($option, $namecolumn, $searchuserparam);
			$query = mysqli_query($connection, "SELECT * FROM `user` WHERE `$searchuserparam` LIKE '$searchuser' ORDER BY `user`.`$searchuserparam` ASC;"); // Поиск по параметрам
		}
		else
		{
			$query = mysqli_query($connection, "SELECT * FROM `user` ORDER BY `user`.`name` ASC");
		}
		/* Поиск по пользователям. Конец. */
		/* Вывод пользователей. Начало: */
		if (mysqli_num_rows($query) == 0)
		{
			echo 'Нечего не найдено!';
		}
		?>
		<h3>Пользователи:</h3>
		<table>
			<tr>
			    <th>Имя:</th>
			    <th>Ид:</th> 
			    <th>Почта:</th>
			    <th><i class="far fa-comment"></i></th>
			    <th><i class="far fa-newspaper"></i></th>
			    <th><div class="hidden">Действия:</div></th>
	 		</tr>
			<?php 

			while ($user = mysqli_fetch_assoc($query)) 
			{ 			
				$countcomment = mysqli_query($connection, "SELECT `id` FROM `comment` WHERE `autorid` = {$user['id']};");
				$countpost = mysqli_query($connection, "SELECT `id` FROM `post` WHERE `autorid` = {$user['id']};");

				?>
				
				<tr>
					<td>
						<?php echo $user['name']; 
						if ($user['usergroup'] == '2') 
						{
							echo ' (Забанен)';
						}
						elseif ($user['usergroup'] == '1') 
						{
						 	echo ' (Админ)';
						} ?>		
					</td>
					<td><?php echo $user['id']; ?></td>
					<td><?php echo $user['email']; ?></td>
					<td><?php echo sqlarray($countcomment); ?></td>
					<td><?php echo sqlarray($countpost); ?></td>
					<td>
						<li class="flex">
							<div class="dropdown">
								<span class="dropbtn action">
									<span class="flex"><i class="fas fa-caret-down"></i><div class="hidden">Действие</div></span>
								</span>
								<div class="dropdown-content">
									<?php 
									if ($user['usergroup'] != 1) 
									{
										echo "<a href=\"admin.php?mod=user&newgroup=1&usergroup={$user['id']}\">Сделать админом</a>";
									}
									else
									{
										echo "<a href=\"admin.php?mod=user&newgroup=0&usergroup={$user['id']}\">Снять админку</a>";
									}
									?>
									
									<a href="admin.php?mod=user&edituser=<?php echo $user['id'] ?>">Редактировать</a>
									<?php 
									if ($user['usergroup'] != 2) 
									{
										echo "<a href=\"admin.php?mod=user&usergroup={$user['id']}&newgroup=2\">Забанить</a>";
									}
									else
									{
										echo "<a href=\"admin.php?mod=user&newgroup=0&usergroup={$user['id']}\">Разбанить</a>";
									}
									?>
									<a href="admin.php?mod=user&deleteusercomment=<?php echo $user['id'] ?>">Удалить все коментарии</a>
									<a href="admin.php?mod=user&deleteuserpost=<?php echo $user['id'] ?>">Удалить все посты</a>
									<a href="admin.php?mod=user&deleteuser=<?php echo $user['id'] ?>">Удалить</a>
								</div>
							</div> 
						</li>
					</td>
				</tr>	
	  		<?php 
	  		} /* Вывод пользователей. Конец. */ ?>
	    </table>
	</div>
	<div></div>
<?php 
}
elseif ($_GET['mod'] == 'category')
{  
	/* Удаление категорий. Начало: */
	if (!empty($_GET['deletecategory']))
	{
		if (is_numeric($_GET['deletecategory'])) 
		{
			// Получаем название категории
			$selectcategory = mysqli_query($connection, "SELECT `name` FROM `category` WHERE `id` = {$_GET['deletecategory']};");
			$selectcategory = mysqli_fetch_assoc($selectcategory);
			$selectcategory = $selectcategory['name'];
			// Делаем запрос на вывод постов с названием категории
			$selectcategory = mysqli_query($connection, "SELECT `id` FROM `post` WHERE `category` = '$selectcategory';");
			$selectcategory = mysqli_fetch_assoc($selectcategory);
			$selectcategory = $selectcategory['id'];
			// Если постов нет то просто удаляем категорию
			if ($selectcategory == NULL)
			{
				mysqli_query($connection, "DELETE FROM `category` WHERE `id` = {$_GET['deletecategory']};");
				echo
				"<script type='text/javascript'>
			    window.location = 'admin.php?mod=category';
			    </script>";
			}
			// Если в категории есть посты то выбираем куда их переместить
			else
			{
				?>
				<div id="wrap_preloader">
					<form class="editcategory" method="POST">
						<label for="categorylist">Выберите в какую категорию переместить посты:</label>
						<a href="admin.php?mod=category"><i class="fas fa-times fa-3x"></i></a>
						<select name="categorylist">
							<?php

							$selectcategory = mysqli_query($connection, "SELECT * FROM `category`;");

							while ($categorylist = mysqli_fetch_assoc($selectcategory))
							{ 
								if ($categorylist['id'] != $_GET['deletecategory']) 
								{
									echo '<option>' . $categorylist['name'] . '</option>';
								}
								else
								{
									$namedeletecategory = $categorylist['name'];
								}		
							}
							?>
						</select>
						<input value="Изменить" type="submit">
					</form>
				</div>
		<?php 
				if (!empty($_POST['categorylist'])) 
				{
					$deletecategory = $_GET['deletecategory'];
					$categorylist = $_POST['categorylist'];
					mysqli_query($connection, "UPDATE `post` SET `category` = '$categorylist' WHERE `category` = '$namedeletecategory';");
					mysqli_query($connection, "DELETE FROM `category` WHERE `id` = '$deletecategory';");
					echo
					"<script type='text/javascript'>
				    window.location = 'admin.php?mod=category';
				    </script>";
				}
			}
		}
	}
	/* Удаление категорий. Конец. */
	/* Добавление новои категории. Начало: */
	$message = '';
	if (!empty($_POST['newcategory'])) 
	{
		$newcategory = $_POST['newcategory'];
		if (strlen($newcategory) > 1 && strlen($newcategory) < 20) 
		{
			$checkcategory = mysqli_query($connection, "SELECT `id` FROM `category` WHERE `name` = '$newcategory';");
			$checkcategory = mysqli_fetch_assoc($checkcategory);
			if ($checkcategory['id'] != NULL) 
			{
				$message = 'Категория с таким название уже есть!';
			}
			else
			{
				mysqli_query($connection, "INSERT INTO `category` (`name`) VALUES ('$newcategory');");
			}
		}
		else
		{
			$message = 'Не корректная длина названия категории!';
		}
	}
	?>
<div class="column width">
	<form method="post" class="postform">
		<div><?php echo $message; ?></div>
		<input class="newcategory" name="newcategory" type="text">
		<input class="btn" value="Добавить категорию" type="submit">
	</form>

	<ul class="categorylist">
	<?php 
	/* Добавление новои категории. Конец. */
	/* Переименование категории. Начало: */
	if (!empty($_POST['rename'])) 
	{
		$rename = $_POST['rename'];
		$categoryname = $_POST['categoryname'];
		if (strlen($rename) > 1 && strlen($rename) < 20) 
		{
			$checkcategory = mysqli_query($connection, "SELECT `id` FROM `category` WHERE `name` = '$rename';");
			$checkcategory = mysqli_fetch_assoc($checkcategory);
			if ($checkcategory['id'] != NULL) 
			{
				$message = 'Категория с таким название уже есть!';
			}
			else
			{
				mysqli_query($connection, "UPDATE `category` SET `name` = '$rename' WHERE `name` = '$categoryname';");
				mysqli_query($connection, "UPDATE `post` SET `category` = '$rename' WHERE `category` = '$categoryname';");
			}
		}
		else
		{
			$message = 'Не корректная длина названия категории!';
		}
	}
	echo '<div>' . $message . '</div>';
	/* Переименование категории. Конец. */
	/* Вывод категорий. Начало: */
		$query = mysqli_query($connection, "SELECT * FROM `category` ORDER BY `id` ASC;"); 
		while ($category = mysqli_fetch_assoc($query)) 
		{
	?>
	
		<li class="between">
			<span class="flex"><span>ID:<?php echo $category['id'] . ' ' . $category['name'] . ' '; ?></span>  
			<form style="display: none;" class="rename id<?php echo $category['id']; ?> renameform" method="POST">
				<input type="hidden" name="categoryname" value="<?php echo $category['name']; ?>">
				<input class="renameinp" name="rename" type="text">
				<button type="submit" class="rename"><i class="fas fa-edit"></i></button>
			</form>
				<i class="fas hide<?php echo $category['id']; ?> fa-edit" onclick="rename('.id<?php echo $category['id']; ?>', '.hide<?php echo $category['id']; ?>')"></i>
			</span>
			<span>
				<a href="admin.php?mod=category&deletecategory=<?php echo $category['id'] ?>"><i class="fas fa-trash-alt"></i></a>
			</span>
		</li>
	<?php 
		} ?>
	</ul>
</div>
<div></div>
<script>
function rename(rename,icon)
{
    var show = document.querySelector(rename);
    show.style.display = 'flex';
    var edit = document.querySelector(icon);
    edit.style.display = 'none';
}
</script>
<?php 
	/* Вывод категорий. Конец. */

}
elseif ($_GET['mod'] == 'post') 
{
/* Сткрытие и отображение поста. Начало: */
if (isset($_GET['hide']) && isset($_GET['postid'])) 
{
	if (is_numeric($_GET['hide']) && is_numeric($_GET['postid'])) 
	{
		mysqli_query($connection, "UPDATE `post` SET `hide` = {$_GET['hide']} WHERE `id` = {$_GET['postid']}");
		echo
		"<script type='text/javascript'>
	    window.location = 'admin.php?mod=post';
	    </script>";
	}
}
/* Сткрытие и отображение поста. Конец. */
/* Удаление поста. Начало: */
if (isset($_GET['postdelete'])) 
{
	if (is_numeric($_GET['postdelete'])) 
	{
		$query = mysqli_query($connection, "SELECT `image` FROM `post` WHERE `id` =  {$_GET['postdelete']};");
		$image = mysqli_fetch_assoc($query);
		unlink($image['image']);
		mysqli_query($connection, "DELETE FROM `post` WHERE `id` = {$_GET['postdelete']};");
		mysqli_query($connection, "DELETE FROM `comment` WHERE `id` = {$_GET['postdelete']};");
		echo
		"<script type='text/javascript'>
	    window.location = 'admin.php?mod=post';
	    </script>";
	}
}
/* Удаление поста. Конец. */
/* Удаление коментариев поста. Начало: */
if (isset($_GET['delete']) && isset($_GET['postid'])) 
{
	if (is_numeric($_GET['delete']) && is_numeric($_GET['postid'])) 
	{
		mysqli_query($connection, "DELETE FROM `comment` WHERE `postid` = {$_GET['postid']};");
		echo
		"<script type='text/javascript'>
	    window.location = 'admin.php?mod=post';
	    </script>";
	}
}
/* Удаление коментариев поста. Конец. */
/* Редактирование поста. Начало: */
if (!empty($_GET['editpost'])) 
	{
		if (is_numeric($_GET['editpost']))
		{
			echo '<div id="wrap_preloader">';
			$query = mysqli_query($connection, "SELECT * FROM `post` WHERE `id` = {$_GET['editpost']};");
			$post = mysqli_fetch_assoc($query);
			$title = $post['title'];
			$description = $post['text'];
			$category = $post['category'];
			$date = $post['date'];
			$autorid = $post['autorid'];
			if (count($_POST) > 0) 
			{
				$title = clean($_POST['title']);
				$description = clean($_POST['description']);
				$date = clean($_POST['date']);
				$autorid = clean($_POST['autorid']);
				$category = $_POST['category'];
				$query = mysqli_query($connection, "SELECT * FROM `user` WHERE `id` = '$autorid';");
				$user = mysqli_fetch_assoc($query);
				$name = $user['name'];
				if (!empty($description) && !empty($date) && !empty($autorid)) 
				{
					if (empty($title)) 
					{
						$temp = substr($description, 0, 30);
						$title = substr($temp, 0, strrpos($temp, ' '));
					}
					if (strlen($title) > 100 || strlen($title) < 5)
					{
						echo '<span class="alert">Заголовок должен варироватся от 10 до 100 символов</span>';
					}
					elseif (strlen($description) > 6000 || strlen($description) < 250)
					{
						echo '<span class="alert">Размер стати должен быть от 500 до 6000 символов</span>';
					}
					elseif (!preg_match("/^20[0-9]{2}+-[0-1]{1}[0-9]{1}+-[0-3]{1}[0-9]{1}+[ ]{1}+[0-2]{1}[0-9]{1}+:[0-5]{1}[0-9]{1}+:[0-5]{1}[0-9]{1}$/", $date)) 
					{
					    echo '<span class="alert">Дата должна быть введена в формате 2000-01-01 00:00:00!</span>';
					}
					elseif ($name == NULL)
					{
						echo '<span class="alert">Пользователь не найден!</span>';
					}
					elseif (!empty($_FILES)) 
					{
						if($_FILES['image']['type'] == "image/jpeg") 
						{
							$ending = '.jpg';
						}
						elseif ($_FILES['image']['type'] == "image/png") 
						{
							$ending = '.png';
						}
						else
						{
							$ending = NULL;
						}
						if ($ending != '.jpg' && $ending != '.png') 
						{
							echo '<span class="alert">Фото не найдено или имеет не коректное расширение. Изображение может иметь только расширение .jpg, .jpeg, .png</span>';
						}
						else
						{
							unlink('images/postid-' . $_GET['editpost'] . '.png');
							unlink('images/postid-' . $_GET['editpost'] . '.jpg');
							$uploaddir = 'images/';
							$uploadfile = $uploaddir . "postid-{$_GET['editpost']}$ending";
							$tmp_name = $_FILES['image']['tmp_name'];
							move_uploaded_file($tmp_name, $uploadfile);
							mysqli_query($connection, "UPDATE `post` SET `image` = '$uploadfile', `title` = '$title', `text` = '$description', `category` = '$category', `date` = '$date', `autor` = '$name', `autorid` = '$autorid' WHERE `post`.`id` = {$_GET['editpost']};");
								echo
								"<script type='text/javascript'>
							    window.location = 'admin.php?mod=post';
							    </script>";
						}
					}
					else
					{
						mysqli_query($connection, "UPDATE `post` SET `title` = '$title', `text` = '$description', `category` = '$category', `date` = '$date', `autor` = '$name', `autorid` = '$autorid' WHERE `post`.`id` = {$_GET['editpost']};");	
						echo
						"<script type='text/javascript'>
					    window.location = 'admin.php?mod=post';
					    </script>";
						unset($_POST);
					}
				}
				else
				{
					echo '<span class="alert">Заполните все поля!</span>';
				}		
			}		
			?>
		<form class="editpost" method="POST" enctype="multipart/form-data">
			<label for="name">Изменить заголовок:</label>
			<a href="admin.php?mod=post"><i class="fas fa-times fa-3x"></i></a>
			<input name="title" type="text" value="<?php echo $title; ?>">
			<label for="description">Изменить текст:</label>
			<textarea name="description" cols="30" rows="10" required><?php echo $description; ?></textarea>
			<label style="margin-bottom: 0;" for="image">Изменить лицевая картинка:</label>
			<span>(если не хотите менять пропустите это поле)</span>
			<input class="file" name="image" type="file">
			<label for="date">Дата добавления:</label>
			<input type="text" name="date" value="<?php echo $date; ?>">
			<label for="autorid">Ид автора:</label>
			<input type="text" name="autorid" value="<?php echo $autorid; ?>">
			<label for="category">Изменить категорию:</label>
			<select name="category">
				<?php  
					$query = mysqli_query($connection, "SELECT `name` FROM `category` ORDER BY `category`.`name` ASC;");
					echo '<option>' . $category . '</option>';
					while ($categ = mysqli_fetch_assoc($query)) 
					{
						if ($categ['name'] != $category)
						{
							echo '<option>' . $categ['name'] . '</option>';
						}
					}
				?>
			</select>
			<input class="btn submit" type="submit" value="Изменить">
		</form></div>
			<?php
		} 
	}	
/* Редактирование поста. Конец. */
/* Поиск по постам. Начало: */
?>
<div class="width">
	<h3>Поиск:</h3>
	<form class="flex" style="margin: 0;" method="POST">
		<input class="shortinp" style="" name="searchpost" type="text">
		<span>
			<input style="margin: 0;" type="radio" name="coincidence" value="coincidence"> Точное совпадение
		</span>
		<select class="postform" name="searchpostparam">
			<option>название</option>
			<option>ид автора</option>
			<option>ид</option>
			<option>автор</option>
			<option>дата</option>
			<option>категория</option>
		</select>
		<input class="btn postform" type="submit" value="Поиск">
	</form>
<?php 
	if (!empty($_POST['searchpost']))
	{	
		if (empty($_POST['coincidence']))
		{
			$searchpost = '%' . $_POST['searchpost'] . '%';
		}
		else
		{
			$searchpost = $_POST['searchpost'];
		}
		$searchpostparam = $_POST['searchpostparam'];
		$option = ['название', 'ид автора', 'ид', 'автор', 'дата', 'категория'];
		$namecolumn = ['title', 'autorid', 'id', 'autor', 'date', 'category'];
		$searchpostparam = str_replace($option, $namecolumn, $searchpostparam);
		$query = mysqli_query($connection, "SELECT * FROM `post` WHERE `$searchpostparam` LIKE '$searchpost' ORDER BY `post`.`$searchpostparam` ASC;"); // Поиск по параметрам
	}
	else
	{
		$query = mysqli_query($connection, "SELECT * FROM `post` ORDER BY `post`.`date` DESC");
	}
/* Поиск по постам. Конец. */
/* Вывод попостов. Начало: */
?>
	<h3>Посты:</h3>
	<table>
		<tr>
		    <th>Название:</th>
		    <th>Ид:</th>
		    <th>Автор:</th>
		    <th>Ид автора:</th>
		    <th><i class="far fa-clock"></th> 
		    <th><i class="far fa-comment"></i></th>
		    <th><i class="fas fa-eye"></i></th>
		    <th><i class="fas fa-tags"></i></th>
		    <th><div class="hidden">Действия:</div></th>
		</tr>
	<?php	
		$autorid = $_SESSION['user']['id'];
		//$post1 = mysqli_fetch_assoc($query);

		while ($post = mysqli_fetch_assoc($query)) 
		{	
			$comment = mysqli_query($connection, "SELECT `id` FROM `comment` WHERE `postid` = {$post['id']};");
	?>
		<tr>
			<td>
				<?php echo $post['title']; 
				if ($post['hide'] != 0) 
				{
					echo ' (Скрыто)';
				}
				?>
			</td>
			<td><?php echo $post['id']; ?></td>
			<td><?php echo $post['autor']; ?></td>
			<td><?php echo $post['autorid']; ?></td>
			<td><?php echo $post['date']; ?></td>
			<td><?php sqlarray($comment); ?></td>
			<td><?php echo $post['views']; ?></td>
			<td><?php echo $post['category']; ?></td>
			<td>
				<li class="flex ">
					<div class="dropdown mypost">
						<span class="dropbtn flex action">
							<i class="fas fa-caret-down"></i><div class="hidden">Действие</div>
						</span>
						<div class="dropdown-content">
							<a href="admin.php?mod=post&editpost=<?php echo $post['id']; ?>">Редактировать</a>
							<a href="post.php?id=<?php echo $post['id']; ?>">Посмотреть</a>
							<?php 
							if ($post['hide'] == 0) 
							{
								echo '<a href="admin.php?mod=post&postid=' . $post['id'] . '&hide=1">Скрыть</a>';
							}
							else
							{
								echo '<a href="admin.php?mod=post&postid=' . $post['id'] . '&hide=0">Отображать</a>';
							}
							?>
							<a href="post.php?id=<?php echo $post['id']; ?>">Удалить все коментарии</a>
							<a href="admin.php?mod=post&postdelete=<?php echo $post['id']; ?>">Удалить</a>
						</div>
					</div> 
				</li>
			</td>
		</tr>
	<?php
		}
	} 
	/* Вывод попостов. Конец. */?>
	</table>

</div>
	<div></div>
</div>

</body>
</html> 
