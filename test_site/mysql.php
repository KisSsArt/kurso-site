<?php

$ini;
$db_connection;

function init_database_connection()
{
	global $ini;
	global $db_connection;
	
	$ini = parse_ini_file('database_setup.ini');
	
	$db_connection = mysqli_connect($ini['db_host'], $ini['db_user_name'], $ini['db_password'], $ini['db_name']);

	if (!$db_connection) {
		die("connection failed");
	}

	function print_product_list()
	{
		global $db_connection;
	
		$sql_request = "SELECT Id, name, count, price FROM Products";

		$query_result = mysqli_query($db_connection, $sql_request);
	
		print("Products List:<br>");
		while ($row = mysqli_fetch_array($query_result)) {
			print("Id: ".$row['Id']." | Name: ".$row['name']." | Count: ".$row['count']." | Price: ".$row['price']."р<br>");
		}
	}
	
	function print_user_list()
	{
		global $db_connection;
	
		$sql_request = "SELECT Id, name, access FROM users";
		
		$query_result = mysqli_query($db_connection, $sql_request);
		
		print("Users List:<br>");
		while ($row = mysqli_fetch_array($query_result)) {
			print("Id: ".$row['Id']." | Name: ".$row['name']." | Access: ".$row['access']."<br>");
		}
	}
	
	function remove_product($product_name, $product_count)
	{	
		global $db_connection;
	
		$name = mysqli_real_escape_string($db_connection, $product_name);
		
		$query_name_request = "SELECT * FROM Products WHERE name='{$name}'";
		
		$name_query = mysqli_query($db_connection, $query_name_request);
		
		$products_count = mysqli_num_rows($name_query);
		
		if ($products_count != 0)
		{
			$name_row = mysqli_fetch_array($name_query);
			
			$delete = $product_count >= $name_row['count'];
			
			if ($delete)
			{
				$delete_product = "DELETE FROM Products WHERE name='{$name_row['name']}'";
				
				mysqli_query($db_connection, $delete_product);
			}
			else
			{
				$new_count = $name_row['count'] - $product_count;
				
				$update_count = "UPDATE Products SET count='{$new_count}' WHERE Id='{$name_row['Id']}'";
				
				mysqli_query($db_connection, $update_count);
			}
		}
	}
	
	function verify_password($username, $password)
	{
		global $db_connection;
	
		$name = mysqli_real_escape_string($db_connection, $username);
	
		$query_name_request = "SELECT * FROM users WHERE name='{$name}'";
	
		$name_query = mysqli_query($db_connection, $query_name_request);
	
		$name_count = mysqli_num_rows($name_query);
	
		if ($name_count == 0)
			return 0;
			
		$name_row = mysqli_fetch_array($name_query);
			
		if ($name_row['password'] == $password)
			return $name_row['Id'];
		else
			return 0;
	}
	
	function get_username_by_Id($id)
	{
		global $db_connection;
		
		$query_id_request = "SELECT * FROM users WHERE Id='{$id}'";
		
		$id_query = mysqli_query($db_connection, $query_id_request);
	
		$id_count = mysqli_num_rows($id_query);
	
		if ($id_count == 0)
			return "unknown";
			
		$id_row = mysqli_fetch_array($id_query);
			
		return $id_row['name'];
	}
	
	function get_access_by_username($username)
	{
		global $db_connection;
		
		$query_name_request = "SELECT * FROM users WHERE name='{$username}'";
		
		$name_query = mysqli_query($db_connection, $query_name_request);
	
		$name_count = mysqli_num_rows($name_query);
	
		if ($name_count == 0)
			return 0;
			
		$name_row = mysqli_fetch_array($name_query);
			
		return $name_row['access'];
	}
}

?>
