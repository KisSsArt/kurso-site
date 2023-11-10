<?php include "mysql.php"; ?>
<html>
<h1>Authorization</h1>
<form method="post" action="" name="authform">
	<p><lable>
		Username:<br>
		<input name="username" size="25", type="text">
	</lable></p>

	<p><lable>
		Password:<br>
		<input name="password" size="25", type="password">
	</lable></p>

	<button type="submit" name="login" value="login">Sign In</button>
</form>
</html>
<?php
	session_start();
	
	init_database_connection();
	
	if (isset($_POST['login']))
	{
		$user_id = verify_password($_POST['username'], $_POST['password']);
		if ($user_id != 0)
		{
			$_SESSION['user_id'] = $user_id;
			$_SESSION['access'] = get_access_by_username($_POST['username']);
			header("Location: /index.php");
		}
		else
		{
			echo "Неверный пароль или имя пользователя";
		}
	}
?>
