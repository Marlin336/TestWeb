<?php
	$conn = new mysqli('localhost', 'guest', '', 'testdb') or die("Не удалось покдлючиться к базе данных");
	$in_name = $conn->real_escape_string($_POST['name']);
	$in_email = $conn->real_escape_string($_POST['email']);
	$in_id = $conn->real_escape_string($_POST['id']);
	$conn->query("UPDATE `user` SET `name` = '$in_name', `email` = '$in_email' WHERE `id` = '$in_id'");
?>