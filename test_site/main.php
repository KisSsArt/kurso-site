<?php include 'mysql.php'; ?>
<?php
session_start();

if (!isset($_SESSION['user_id']))
{
	header("Location: auth.php");
	exit;
}

init_database_connection();

echo "Loggined as: " . get_username_by_Id($_SESSION['user_id']);
?>
<html>
	<form action="" method="post" name="signoutform">
		<button type="submit" name="admin_panel" value="admin_panel">Admin Panel</button>
		<button type="submit" name="sign_out" value="sign_out">Sign Out</button>
	</form>
	
	<hr>
</html>
<html>
	<h1>ПУНКТ ВЫДАЧИ</h1>
</html>
<?php
	print_product_list();
?>
	<html>
	<body>
		<h1>Sell Products</h1>
		<form action="" method="post" name="sellform">
			<p><lable>
				Name:<br>
				<input name="s_product_name" size="25", type="text">
			</lable></p>

			<p><lable>
				Count:<br>
				<input name="s_product_count" size="25", type="number">
			</lable></p>

			<p><input name="sell" type="submit" value="Sell"></p>
		</form>
	</body>
	</html>
<?php
	if (isset($_POST["sign_out"]))
	{
		session_unset();
		
		header("Location: /index.php");
	}
	
	if (isset($_POST["admin_panel"]))
	{
		header("Location: /admin/index.php");
	}
	
	if (isset($_POST["sell"]) && !empty($_POST['s_product_name']) && !empty($_POST['s_product_count']))
	{
		remove_product($_POST['s_product_name'], $_POST['s_product_count']);
		
		header("Location: /main.php");
	}
?>
