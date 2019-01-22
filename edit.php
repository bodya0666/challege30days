<?php 
session_start();
include('db.php');
$id = $_GET['id'];
if (empty($id)) {
	echo
	"<script type='text/javascript'>
    window.location = 'mypost.php';
    </script>";
}
if (!empty($_SESSION['user'])) 
{
	$autorid = $_SESSION['user']['id'];
	if (is_numeric($id))
	{
		$query = mysqli_query($connection, "SELECT * FROM `post` WHERE `autorid` = '$autorid' and `id` = '$id';");
		$post = mysqli_fetch_assoc($query);
	}
	if ($post === NULL) 
	{
		echo
		"<script type='text/javascript'>
	    window.location = 'mypost.php';
	    </script>";
	}
	include('apps/header.php');
	function clean($value = "") 
	{
	    $value = trim($value);
	    $value = stripslashes($value);
	    $value = strip_tags($value);
	    $value = htmlspecialchars($value);
	    
	    return $value;
	}
	$title = $post['title'];
	$description = $post['text'];
	if (count($_POST) > 0) 
	{
		$title = clean($_POST['title']);
		$description = clean($_POST['description']);
		if (!empty($description)) 
		{
			$category = $_POST['category'];
			$autor = $_SESSION['user']['name'];
			$autorid = $_SESSION['user']['id'];		
			if (empty($title)) 
			{
				$temp = substr($description, 0, 30);
				$title = substr($temp, 0, strrpos($temp, ' '));
			}
			if (strlen($title) > 100 || strlen($title) < 5)
			{
				echo 'Заголовок должен варироватся от 10 до 100 символов';
			}
			elseif (strlen($description) > 6000 || strlen($description) < 250)
			{
				echo 'Размер стати должен быть от 500 до 6000 символов';
			}
			elseif (!empty($_FILES['image']['name'])) 
			{
				if($_FILES['image']['type'] == "image/jpeg") 
				{
					$ending = '.jpg';
				}
				elseif ($_FILES['image']['type'] == "image/png") 
				{
					$ending = '.png';
				}
				else{
					$ending = NULL;
				}
				if ($ending != '.jpg' && $ending != '.png') 
				{
					echo 'Фото не найдено или имеет не коректное расширение. Изображение может иметь только расширение .jpg, .jpeg, .png';
				}
				else
				{
					unlink('images/postid-' . $id . '.png');
					unlink('images/postid-' . $id . '.jpg');
					$uploaddir = 'images/';
					$uploadfile = $uploaddir . "postid-$id$ending";
					$tmp_name = $_FILES['image']['tmp_name'];
					move_uploaded_file($tmp_name, $uploadfile);
					mysqli_query($connection, "UPDATE `post` SET `image` = '$uploadfile', `title` = '$title', `text` = '$description', `category` = '$category' WHERE `post`.`id` = '$id';");
					echo
					"<script type='text/javascript'>
				    window.location = 'post.php?id=$id';
				    </script>";
				}
			}
			else
			{
				mysqli_query($connection, "UPDATE `post` SET `title` = '$title', `text` = '$description', `category` = '$category' WHERE `post`.`id` = '$id';");	
				echo
				"<script type='text/javascript'>
			    window.location = 'post.php?id=$id';
			    </script>";
				unset($_POST);
			}
		}
		else
		{
			echo 'Заполните все поля!';
		}		
	}
?>

	<div class="center">
		<form class="addpost" method="POST" enctype="multipart/form-data">
			<label for="name">Изменить заголовок:</label>
			<input name="title" type="text" value="<?php echo $title; ?>">
			<label for="description">Изменить текст:</label>
			<textarea name="description" cols="30" rows="10" required><?php echo $description; ?></textarea>
			<label style="margin-bottom: 0;" for="image">Изменить лицевая картинка:</label>
			<span>(если не хотите менять пропустите это поле)</span>
			<input class="file" name="image" type="file">
			<label for="category">Изменить категорию:</label>
			<select name="category">
				<?php  
					$query = mysqli_query($connection, "SELECT `name` FROM `category` ORDER BY `category`.`name` ASC;");
					echo '<option>' . $post['category'] . '</option>';
					while ($categ = mysqli_fetch_assoc($query)) 
					{
						if ($categ['name'] != $post['category'])
						{
						echo '<option>' . $categ['name'] . '</option>';
						}
					}
				?>
			</select>
			<input class="submit" type="submit" value="Изменить">
		</form>
	</div>

<?php 
	include('apps/footer.php');
}
else
{
	header("Location: login.php");
}
?>

