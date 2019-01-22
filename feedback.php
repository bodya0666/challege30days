<?php 
session_start();
include('db.php');
include('apps/header.php'); 
function clean($value = "") 
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value, ENT_QUOTES);
  
    return $value;
}
if (count($_POST) > 0) 
{
	if (!empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['message'])) 
	{
		$email = clean($_POST['email']);
		$subject = clean($_POST['subject']);
		$message = clean($_POST['message']);
		if (strlen($email) < 6 || !preg_match('/[A-Z]/i', $email) || !preg_match('/[@]/', $email) || strlen($email) > 40)
		{ 
			echo 'Email введен не коректно!';
		}
		elseif (strlen($subject) < 6 || strlen($subject) > 40) 
		{
			echo 'Не коректная длина темы!';
		}
		elseif (strlen($message) < 10 || strlen($message) > 1000) 
		{
			echo 'Не коректная длина сообщения!';
		}
		else
		{
			$headers = "From: $email" . "\r\n" .
		    'Reply-To: maximchuk.bodya@gmail.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
			mail('maximchuk.bodya@gmail.com', $subject, $message, $headers);
		}
	}
	else
	{
		echo 'Заполните все поля!';
	}
}
?>
<div class="center">
	<form method="POST">
		<label for="email">Email:</label>
		<input name="email" type="text">
		<label for="subject">Тема:</label>
		<input name="subject" type="text">
		<label for="message">Сообщение:</label>
		<textarea name="message" type="text"></textarea>
		<input class="btn submit" type="submit">
	</form>
</div>
<?php include('apps/footer.php'); ?>