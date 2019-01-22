<?php 

session_start();
include('db.php');
include('apps/header.php');
if (!empty($_SESSION['user'])) 
{
	echo
	"<script type='text/javascript'>
    window.location = 'index.php';
    </script>";
}
$email = "";
$password = "";

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"  type="text/javascript"></script>
<script>
$(document).ready(function()
{
  $('.btn').click(function()
  {
    var email = $('#email').val();
    var password = $('#password').val();
    var remember = $('#remember').val();
    $.ajax({ 
      url: "auth.php", // куда отправляем
      type: "post", // метод передачи
      dataType: "json", // тип передачи данных
      data: 
      { // что отправляем
        "email":   email,
        "password":    password,
        "remember":    remember
      },
      // после получения ответа сервера
      success: function(data)
      {
        if(data.result == "success")
        {
        	$('#container').html(data.error);
        }
        else
        {
          	$('#container').html(data.noerror);
        }
      }
    });
  });
});     
</script>
	<div class="center">
		<form method="POST">
			<div id="container"></div>
			<label class="email" for="email">Email:</label>
			<input id="email" type="email" name="email" value="<?php echo $email; ?>" required>
			<label for="password">Пароль:</label>
			<input id="password" type="password" name="password" value="<?php echo $password; ?>" autocomplete="on" required>
			<span><input style="margin: 0;" id="remember" type="checkbox" name="remember" value="remember"> Запомнить меня</span>
			<div class="btn submit"  style="height: 40px; width: 310px; padding: 0;" >войти</div>
		</form>
	</div>
<?php include('apps/footer.php');?>
