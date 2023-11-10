<?php include '../mysql.php'; ?>
<?php
session_start();

if (!isset($_SESSION['user_id']))
{
	header("Location: ../auth.php");
	exit;
}

if (!isset($_SESSION['access']))
{
	header("Location: ../auth.php");
	exit;
}
else
{
	if ($_SESSION['access'] < 1)
	{
		header("Location: ../index.php");
		exit;
	}
}

init_database_connection();

echo "Loggined as: " . get_username_by_Id($_SESSION['user_id']);
?>
<html>
	<form action="" method="post" name="signoutform">
		<button type="submit" name="point_panel" value="point_panel">ПУНКТ ВЫДАЧИ</button>
		<button type="submit" name="sign_out" value="sign_out">Sign Out</button>
	</form>
	
	<hr>
</html>
<html>
	<h1>Admin Panel</h1>
</html>
<?php
	print_product_list();
?>
<html>
	<body>
		<h1>Add Products</h1>
		<form action="" method="post" name="addform">
			<p><lable>
				Name:<br>
				<input name="product_name" size="25", type="text">
			</lable></p>

			<p><lable>
				Count:<br>
				<input name="product_count" size="25", type="number">
			</lable></p>
			
			<p><lable>
				Price(rub):<br>
				<input name="product_price" size="25", type="number">
			</lable></p>

			<p><input name="add" type="submit" value="Add"></p>
		</form>
		
		<h1>Remove Products</h1>
		<form action="" method="post" name="removeform">
			<p><lable>
				Name:<br>
				<input name="r_product_name" size="25", type="text">
			</lable></p>

			<p><lable>
				Count:<br>
				<input name="r_product_count" size="25", type="number">
			</lable></p>

			<p><input name="remove" type="submit" value="Remove"></p>
		</form>
	</body>
	
	<hr>
</html>
<?php
	print_user_list();
?>
<html>
	<h1>Add New User</h1>
	<form action="" method="post" name="adduserform">
		<p><lable>
			Name:<br>
			<input name="user_name" size="25", type="text">
		</lable></p>

		<p><lable>
			Password:<br>
			<input name="user_password" size="25", type="password">
		</lable></p>
			
		<p><lable>
			Access:<br>
			<input name="access" size="25", type="number">
		</lable></p>

		<p><input name="addUser" type="submit" value="Add User"></p>
	</form>
</html>
<?php

	if (isset($_POST["sign_out"]))
	{
		session_unset();
		
		header("Location: ../index.php");
	}
	
	if (isset($_POST["point_panel"]))
	{
		header("Location: ../main.php");
	}

	if (isset($_POST["add"]) && !empty($_POST['product_name']) && !empty($_POST['product_count']) && !empty($_POST['product_price']))
	{
		$name = mysqli_real_escape_string($db_connection, $_POST['product_name']);
		
		$query_name_request = "SELECT * FROM Products WHERE name='{$name}'";
		
		$name_query = mysqli_query($db_connection, $query_name_request);
		
		$products_count = mysqli_num_rows($name_query);
		
		if ($products_count == 0)
		{
			// product doesnt exist
			$add_new_product = "INSERT INTO Products(name, count, price) VALUES('{$name}', '{$_POST['product_count']}', '{$_POST['product_price']}')";
			
			mysqli_query($db_connection, $add_new_product);
		}
		else
		{
			// product was finded
			$name_row = mysqli_fetch_array($name_query);
			
			$new_count = $_POST['product_count'] + $name_row['count'];
			
			$update_count = "UPDATE Products SET count='{$new_count}' WHERE Id='{$name_row['Id']}'";
			
			mysqli_query($db_connection, $update_count);
		}
		
		header("Location: /admin/index.php");
	}
	
	if (isset($_POST["addUser"]) && !empty($_POST['user_name']) && !empty($_POST['user_password']) && !empty($_POST['access']))
	{
		$name = mysqli_real_escape_string($db_connection, $_POST['user_name']);
		$password = mysqli_real_escape_string($db_connection, $_POST['user_password']);
		
		$add_new_product = "INSERT INTO users(name, password, access) VALUES('{$name}', '{$password}', '{$_POST['access']}')";
			
		mysqli_query($db_connection, $add_new_product);
		
		header("Location: /admin/index.php");
	}
	
	if (isset($_POST["remove"]) && !empty($_POST['r_product_name']) && !empty($_POST['r_product_count']))
	{
		remove_product($_POST['r_product_name'], $_POST['r_product_count']);
		
		header("Location: /admin/index.php");
	}
?>
