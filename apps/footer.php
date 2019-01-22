</div>
<?php 
$result = mysqli_query($connection, "SELECT `id` FROM `post` WHERE `hide` = 0;");// This line executes the MySQL query that you typed above

$post = []; // make a new array to hold all your data

$index = 0;
while($row = mysqli_fetch_assoc($result)) // loop to store the data in an associative array.
{
    $post[$index] = $row;
    $index++;
}
$rand_post = array_rand($post);
$query = mysqli_query($connection, "SELECT `category` FROM `post` WHERE `id` = {$post[$rand_post]['id']};");
$category = mysqli_fetch_assoc($query);
?>
	<div class="centerrand">
		<a class="aRandom" href="<?php echo 'post.php?id=' . $post[$rand_post]['id']; ?>">
			<div class="random">
				<div class="randtext">Интересует<br><?php echo $category['category']; ?>?<br>
					Жми сюда!
				</div>	
			</div>
		</a>
	</div>
	</div>
		</div>
			<footer>	
				<ul class="flex footer">
					<li>© 2018 Blog</li>
					<span class="flex">
						<li class="rule"><a href="rule.php"><i class="fas fa-gavel"></i> Правила сайта</a></li>
						<li><a href="feedback.php"><i class="far fa-comments"></i> Обратная связь</a></li>
					</span>
				</ul>
			</footer>
		</div>
	</body>
</html> 