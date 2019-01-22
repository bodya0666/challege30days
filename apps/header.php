<?php 
if (isset($_COOKIE['user_email']) && isset($_COOKIE['user_pass'])) 
{
	$email = $_COOKIE['user_email'];
	$password = $_COOKIE['user_pass'];
 	$query = mysqli_query($connection, "SELECT `name`, `id`, `usergroup` FROM `user` WHERE `email` = '$email' and `password` = '$password' and 'usergroup' < 2;");
 	$userdata = mysqli_fetch_assoc($query);
 	$_SESSION['user'] = ['email' => $email, 'name' => $userdata['name'], 'id' => $userdata['id'], 'usergroup' => $userdata['usergroup']];
} ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="stylesheet/style.css">
	<link rel="shortcut icon" href="blogimages/logo.jpg">
	<link href="stylesheet/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	<title>Blog</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div class="wrapper">
	<div class="content">
<header>
<ul class="flex footer">
	<li><a href="index.php"><i class="fas fa-home"></i></a></li>
	<li>
	<?php 

		if (empty($_SESSION['user'])) 
		{
			echo '<a href="search.php"><i class="fas fa-search"></i> Поиск</a> | <a href="login.php"><i class="fas fa-sign-in-alt"></i> Войти</a> | <a href="registration.php"><i class="fas fa-user-plus"></i> Зарегистрироватся</a>';	
		}
		else
		{	
			$name = $_SESSION['user']['name'];
		 	echo '<li class=\"flex\">
		 	 <a href="search.php"><i class="fas fa-search"></i> Поиск</a> | <a href="addpost.php"><i class="far fa-newspaper"></i> Добавить новость</a> |' . 
					 " <div class=\"dropdown\">
					  <span class=\"dropbtn\"><i class=\"fas fa-user\"></i> $name</span>
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
<!--Блок с категориями-->
<div class="between column">
	<div class="category">
		<ul class="list">
			<h5>Категории:</h5>
			<?php 
			$query = mysqli_query($connection, "SELECT * FROM `category` ORDER BY `category`.`name` ASC");
			while ($category = mysqli_fetch_assoc($query)) 
			{
				echo '<li><a href="search.php?category=' . $category['name'] . '">' . $category['name'] . '</a></li>';
			}
			?>
		</ul>
	</div>	
<div class="width">
