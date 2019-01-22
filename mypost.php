<?php 
session_start();
include('db.php');
include('apps/header.php');
if (empty($_SESSION)) 
{
	echo
	"<script type='text/javascript'>
    window.location = 'index.php';
    </script>";
}
function sqlarray($query)
{
	$result = [];
	while ($row = mysqli_fetch_array($query))
	{
    	$result[] = $row[0];
	}
	echo count($result);
}
?>
<h3>Посты:</h3>
<table>
	<tr>
	    <th>Название:</th>
	    <th><i class="far fa-clock"></th> 
	    <th><i class="far fa-comment"></i></th>
	    <th><i class="fas fa-eye"></i></th>
	    <th><i class="fas fa-tags"></i></th>
	    <th><div class="hidden">Действия:</div></th>
	</tr>
<?php	
	$autorid = $_SESSION['user']['id'];
	$query = mysqli_query($connection, "SELECT * FROM `post` WHERE `autorid` = '$autorid' ORDER BY `post`.`date` DESC");

	while ($post = mysqli_fetch_assoc($query)) 
	{	
		$comment = mysqli_query($connection, "SELECT `id` FROM `comment` WHERE `postid` = {$post['id']};");
?>
	<tr>
		<td><?php echo $post['title'];
		if ($post['hide'] != 0) 
		{
			echo ' (Скрыто)';
		}
		 ?></td>
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
						<a href="edit.php?id=<?php echo $post['id']; ?>">Редактировать</a>
						<a href="post.php?id=<?php echo $post['id']; ?>">Посмотреть</a>
						<a href="delete.php?id=<?php echo $post['id']; ?>">Удалить</a>
					</div>
				</div> 
			</li>
		</td>
	</tr>
<?php
	}
?>
</table>
	
<?php 
include('apps/footer.php'); 
?>