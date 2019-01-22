<?php 

include('db.php');
function sqlarray($query)
{
  $result = [];
  while ($row = mysqli_fetch_array($query))
  {
      $result[] = $row[0];
  }
  return count($result);
}
$countView = (int)$_POST['count_add'];  // количество записей, получаемых за один раз
$startIndex = (int)$_POST['count_show']; // с какой записи начать выборку
$query = mysqli_query($connection, "SELECT * FROM `post` WHERE `hide` = 0 ORDER BY `post`.`date` DESC LIMIT $startIndex, $countView");

if( mysqli_num_rows($query) == 0)
{
    // если новостей нет
    echo json_encode(array(
        'result' => 'finish'
    ));
}
else
{
    $html = "";
    while($post = mysqli_fetch_array($query))
    {
        $comment = mysqli_query($connection, "SELECT `id` FROM `comment` WHERE `postid` = {$post['id']};");
        $html .= "
    <div class=\"center\">
        <div class=\"row\">
          <div class=\"span8\">
            <div class=\"row\">
              <div class=\"span8\">
                <h4><strong><a href=\"post.php?id={$post['id']}\">{$post['title']}</a></strong></h4>
              </div>
            </div>
            <div class=\"row\">
              <div class=\"span2\">
                
                    <a href=\"#\" class=\"thumbnail\">
                <img src=\"{$post['image']}\" alt=\"\">
            </a> 
              </div>
              <div class=\"span6\">      
                <p>
                    " . substr(substr($post['text'], 0, 220), 0, strrpos(substr($post['text'], 0, 220), ' ')) . '...' . "
                </p>
                <p><a class=\"btn\" href=\"'post.php?id={$post['id']}\">Подробнее</a></p>
              </div>
            </div>
            <div class=\"row\">
              <div class=\"span8\">
                <p></p>
                <p>
                  <i class=\"fas fa-user\"></i> <a href=\"#\">{$post['autor']}</a> 
                  | <i class=\"far fa-calendar\"></i></i> " . substr($post['date'], 0, 16) ."
                  | <i class=\"far fa-comment\"></i> <a href=\"post.php?id={$post['id']}#addcomment\"> " . sqlarray($comment) . " Коментария</a>
                  | <i class=\"far fa-eye\"></i></i> Просмотров: {$post['views']} 
                  | <i class=\"fas fa-tag\"></i> Категория : <a href=\"#\"><span class=\"label label-info\">{$post['category']}</span></a> 
                </p>
              </div>
            </div>
          </div>
        </div>
        <hr>
    </div>
    ";
}
    echo json_encode(array(
        'result'    => 'success',
        'html'      => $html
    ));
}
?>