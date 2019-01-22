<?php 
session_start();
include('db.php');
include('apps/header.php');
$id = $_GET['id'];
mysqli_query($connection, "UPDATE `post` SET `views` = `views` + 1 WHERE `post`.id= '$id';");
$query = mysqli_query($connection, "SELECT * FROM `post` WHERE `id` = '$id';");
$post = mysqli_fetch_assoc($query);

    

?>
<div class="center">
	<div class="row">
	  <div class="span8">
	    <div class="row">
	      <div class="span8">
	        <h4><strong style="font-size: 17.5px;"><?php echo $post['title']; ?></strong></h4>
		    <p>
	          <i class="fas fa-user"></i> <a href="#"><?php echo $post['autor']; ?></a> 
	          | <i class="far fa-calendar"></i></i> <?php echo substr($post['date'], 0, 16); ?>
	          | <i class="far fa-eye"></i></i> <?php echo $post['views']; ?> Просмотров
	          | <i class="fas fa-tag"></i> Категория : <a href="#"><span class="label label-info"><?php echo $post['category']; ?></span></a> 
	        </p>
	      </div>
	    </div>
	    <div class="row">
	    	<a href="#" class="thumbnail">
	            <img src="<?php echo $post['image']; ?>" alt="">
	        </a>
	      
	      <div style="width:100%; margin-top: 15px;" class="span6">      
	        <p>
	        	<?php echo $post['text'];?>
	        </p>
	   
	      </div>
	    </div>		   

	    <div class="row">

	      <div class="span8">
	        <p></p>

	      </div><h4>Коментарии:</h4>
	      <?php 
	      $query = mysqli_query($connection, "SELECT * FROM `comment` WHERE `postid` = '$id' ORDER BY `comment`.`date` ASC");

		  while ($comment = mysqli_fetch_assoc($query)) 
			{ ?>
	      <div class="comment">	      
		        <div>
			      <span class="name"><?php echo $comment['autor']; ?></span>
			      <em class="date"> <?php echo $comment['date']; ?></em>
			    </div>
		        <div>
		      		<?php echo $comment['text']; ?>
		  	    </div>
	      </div>
	  <?php } ?>
	    </div>
	    <h5 id="addcomment" style="padding: 0;">Добавить коментарий:</h5>
		
	    <?php 
	    // Добавление коментария
	    if (!empty($_POST)) 
		{
			$comment = $_POST['comment'];
			$comment = trim($comment);
		    $comment = stripslashes($comment);
		    $comment = strip_tags($comment);
		    $comment = htmlspecialchars($comment, ENT_QUOTES);	
		    if(empty($_SESSION))
		    {
		    	echo 'Сначала надо зарегистрироваться на сайте!';
		    }
			elseif (empty($comment)) 
			{
			    echo 'Сначала напишите что-то!';  
			    	
			}
			elseif (strlen($comment) <= 1 || strlen($comment) > 500)
			{
				echo "Не коретная длина коментария!";
			}
			else
			{
				$autor = $_SESSION['user']['name'];
				mysqli_query($connection, "INSERT INTO `comment` (`text`, `autor`, `postid`, `autorid`) VALUES ('$comment', '$autor', '$id', {$_SESSION['user']['id']});");
				$comment = '';
				echo
				"<script type='text/javascript'>
			        window.location = 'post.php?id=$id';
			        </script>";
			}
		}
	    ?>
	    <form class="postform" method="POST">

	    	<textarea name="comment" class="postform" cols="30" rows="10"></textarea>
	    	<input class="postform" type="submit">
	    </form>
	  </div>
	</div>
	<hr>
</div>

<?php	

include('apps/footer.php');?>
