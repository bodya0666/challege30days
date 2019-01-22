<?php 
session_start();
include('db.php');
include('apps/header.php');
?>
<h3>Поиск:</h3>
<form class="searchform" method="POST">
	<input name="search" type="text">
		<span class="flex between width">
			<select name="searchparam">
				<option>название поста</option>
				<option>текст</option>
				<option>автор</option>
				<option>дата</option>
			</select>
			<select name="category">
				<option>категория</option>
				<?php  
					$query = mysqli_query($connection, "SELECT `name` FROM `category` ORDER BY `category`.`name` ASC;");
					while ($categ = mysqli_fetch_assoc($query)) 
					{
						echo '<option>' . $categ['name'] . '</option>';
					}
				?>
			</select>
			<input class="btn" value="Поиск" style="height:30px" type="submit">
		</span>
</form>
<?php
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
if (!empty($_GET['category'])) 
{
	$category = clean($_GET['category']);
	$query = mysqli_query($connection, "SELECT * FROM `post` WHERE `hide` = '0' and `category` = '$category' ORDER BY `post`.`date` DESC");
}
if (count($_POST) > 0) 
{	
	$search = '%' . clean($_POST['search']) . '%';
	$searchparam = $_POST['searchparam'];
	$category = $_POST['category'];
	$option = ['название поста', 'текст', 'автор', 'дата'];
	$namecolumn = ['title', 'text', 'autor', 'date'];
	$searchparam = str_replace($option, $namecolumn, $searchparam);
	if ($category == 'категория') 
	{
		if ($search == '%%') 
		{
			echo 'Сначала выберите категорию или введите запрос';
		}
		else
		{
			$query = mysqli_query($connection, "SELECT * FROM `post` WHERE `hide` = '0' and `$searchparam` LIKE '$search' ORDER BY `post`.`date` DESC");
		}		
	}
	else
	{
		$query = mysqli_query($connection, "SELECT * FROM `post` WHERE `hide` = '0' and `category` = '$category' and `$searchparam` LIKE '$search' ORDER BY `post`.`date` DESC");
	}
	
}
if (!isset($_GET) && !isset($_POST))
{
	echo 'Введите ваш запрос';
}
if ( mysqli_num_rows($query) == 0 ) {
	echo 'Нечего не найдено!';
}
	while ($post = mysqli_fetch_assoc($query)) 
	{

		$comment = mysqli_query($connection, "SELECT `id` FROM `comment` WHERE `postid` = {$post['id']};");
	?>
		<div class="center">
			<div class="row">
			  <div class="span8">
			    <div class="row">
			      <div class="span8">
			        <h4><strong><a href="<?php echo 'post.php?id=' . $post['id']; ?>"><?php echo $post['title']; ?></a></strong></h4>
			      </div>
			    </div>
			    <div class="row">
			      <div class="span2">
			        <b href="#" class="thumbnail">
			            <img height="180px" width="260px" src="<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>">
			        </b>
			      </div>
			      <div class="span6">      
			        <p>
			        	<?php echo substr(substr($post['text'], 0, 220), 0, strrpos(substr($post['text'], 0, 220), ' ')) . '...';//Обрезает новость до длины 220 символов  ?>
			        </p>
			        <p><a class="btn" href="<?php echo 'post.php?id=' . $post['id']; ?>">Подробнее</a></p>
			      </div>
			    </div>
			    <div class="row">
			      <div class="span8">
			        <p></p>
			        <p>
			          <i class="fas fa-user"></i> <a href="#"><?php echo $post['autor']; ?></a> 
			          | <i class="far fa-calendar"></i></i> <?php echo substr($post['date'], 0, 16); ?>
			          | <i class="far fa-comment"></i> <a href="<?php echo 'post.php?id=' . $post['id']; ?>#addcomment"><?php sqlarray($comment); ?> Коментария</a>
			          | <i class="far fa-eye"></i></i> <?php echo $post['views']; ?> Просмотров
			          | <i class="fas fa-tag"></i> Категория : <a href="#"><span class="label label-info"><?php echo $post['category']; ?></span></a> 
			        </p>
			      </div>
			    </div>
			  </div>
			</div>
			<hr>
		</div>
<?php
	}
?>
<?php include('apps/footer.php'); ?>