<?php 
foreach ($_COOKIE as $key => $value) 
{
	setcookie($key, $value, time() +0);
}
session_start();
session_destroy();
echo
"<script type='text/javascript'>
window.location = 'login.php';
</script>";
?>