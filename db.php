<?php 
$connection = mysqli_connect('localhost', 'root', '', 'blog');
mysqli_set_charset($connection, "utf8");

if($connection == false) 
{
	echo 'Не удалось подключитса к БД!<br>';
	echo mysqli_connect_error();
	exit();
}
?>