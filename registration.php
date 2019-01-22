<?php 
session_start();
include('db.php');
if (!empty($_SESSION['user'])) 
{
	echo
	"<script type='text/javascript'>
    window.location = 'index.php';
    </script>";
}
include('apps/header.php');
$name = '';
$email = '';
$password = '';
$password1 = '';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"  type="text/javascript"></script>
<script>
$(document).ready(function()
{
  $('.btn').click(function()
  {
  	var name = $('#name').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var password1 = $('#password1').val();
    $.ajax({ 
      url: "reg.php", // куда отправляем
      type: "post", // метод передачи
      dataType: "json", // тип передачи данных
      data: 
      { // что отправляем
        "email":   email,
        "password":    password,
        "password1":    password1,
        "name":    name
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
		<label class="email" for="name">Ваше имя:</label>
		<input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
		<label for="email">Email:</label>
		<input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
		<label for="password">Пароль:</label> 
		<input type="password" id="password" name="password" value="<?php echo $password; ?>" required>
		<label for="password1">Подтвеждение пароля:</label>
		<input type="password" id="password1" name="password1" value="<?php echo $password1; ?>" required>
		<div class="btn submit"  style="height: 40px; width: 310px; padding: 0;" >войти</div>
	</form>
</div>
<?php include('apps/footer.php');?>
