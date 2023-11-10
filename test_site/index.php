<?php
	session_start();
	
	if (!isset($_SESSION['user_id']))
	{
		header('Location: auth.php');
		exit;
	}
	else
	{
		if (isset($_SESSION['access']))
		{
			if ($_SESSION['access'] == 0)
				header('Location: main.php');
			else
				header('Location: admin/index.php');
				
			exit;
		}
		else
		{
			header('Location: main.php');
			exit;
		}
	}
?>
