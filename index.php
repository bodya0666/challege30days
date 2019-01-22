<?php 
session_start();
include('db.php');
include('apps/header.php');
$query = mysqli_query($connection, "SELECT * FROM `post` ORDER BY `post`.`date` DESC");

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"  type="text/javascript"></script>
<script>
$(document).ready(function(){

  $('#show_more').click(function()
  {
    var btn_more = $(this);
    var count_show = parseInt($(this).attr('count_show'));
    var count_add  = $(this).attr('count_add');
    btn_more.val('Подождите...');

    $.ajax({
      url: "ajax.php", // куда отправляем
      type: "post", // метод передачи
      dataType: "json", // тип передачи данных
      data: 
      { // что отправляем
        "count_show":   count_show,
        "count_add":    count_add
      },
      // после получения ответа сервера
      success: function(data)
      {
        if(data.result == "success")
        {
          $('#content').append(data.html);
          btn_more.val('Показать еще');
          btn_more.attr('count_show', (count_show + 5));
        }
        else
        {
          btn_more.hide();
        }
      }
    });
  });

});     
</script>
<div id="content">
    <?php
        // выведем в самом начале 5 статей

         
        $query = mysqli_query($connection, "SELECT * FROM `post` WHERE `hide` = 0 ORDER BY `post`.`date` DESC LIMIT 5");
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
                
                    <a href="#" class="thumbnail">
                <img src="<?php echo $post['image']; ?>" alt="">
            </a>
                
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
                  | <i class="far fa-eye"></i></i> Просмотров: <?php echo $post['views']; ?> 
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
</div>
 
<div class="center"><input id="show_more" class="btn" count_show="5" count_add="5" type="button" value="Показать еще"></div>
<?php include('apps/footer.php');?>