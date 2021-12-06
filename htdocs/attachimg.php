<?php
	if($_FILES['photo']['name'])
	{
		$user = 'guest';
		$password = '';
		$db = 'testdb';
	
		$conn = new mysqli('localhost', $user, $password, $db) or die("Не удалось подключиться к БД");
		$user_id = str_replace('"', '', $_POST['uid']);
		$uploaddir = 'photo/';
		$uploadfile = $uploaddir.basename('user_'.$user_id.'.jpg');
		move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile);
		$uploadfile = $conn->real_escape_string($uploadfile);
		$sql = "UPDATE `testdb`.`user` SET photo='".$uploadfile."' WHERE id = ".$user_id or die(mysqli_error($conn));
		$conn->query($sql) or die(mysqli_error($conn));
		echo json_encode($sql);
	}

?>