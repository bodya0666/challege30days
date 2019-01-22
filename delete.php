<?php 
include('db.php');
session_start();
$id = $_GET['id'];
if(is_numeric($id)){
	$autorid = $_SESSION['user']['id'];
	$query = mysqli_query($connection, "SELECT `image` FROM `post` WHERE `autorid` = '$autorid' and `id` = '$id';");
	$post = mysqli_fetch_assoc($query);// Получение путя к картинке

	mysqli_query($connection, "DELETE FROM `post` WHERE `autorid` = '$autorid' and `id` = '$id';");// Удаление статьи

	$query = mysqli_query($connection, "SELECT `image` FROM `post` WHERE `autorid` = '$autorid' and `id` = '$id';");// Запрос после удаления
	if (mysqli_num_rows($query) == 0)// если статьи больше нет 
	{
		unlink($post['image']);// Удаление картинки
	}
	echo
	"<script type='text/javascript'>
    window.location = 'mypost.php';
    </script>";
}

?>