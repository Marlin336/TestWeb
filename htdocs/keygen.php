<?php
	$salt = random_bytes(64);
	$hash = hash('md5', $_POST['name'].$salt);
	
	$conn = new mysqli('localhost', 'guest', '', 'testdb') or die("Не удалось подключиться к БД");
	$conn->query("UPDATE `user` SET `key`='".$hash."' WHERE `id` = '".$_POST['uid']."'");
	// Создать событие удаления `key` из таблицы
	$conn->query("CREATE OR REPLACE EVENT user_".$_POST['uid']."_key ON SCHEDULE AT CURRENT_TIMESTAMP+INTERVAL 1 HOUR DO UPDATE `user` SET `key`=null WHERE `id`='".$_POST['uid']."'");
	echo $hash;
?>