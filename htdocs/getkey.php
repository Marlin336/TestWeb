<?php
	$respond = array('err'=>0);
	if(isset($_POST['key']) && $_POST['key']){
		$conn = new mysqli('localhost', 'guest', '', 'testdb') or die("Не удалось подключиться к базе данных");
		$sql = "SELECT EXISTS (SELECT * FROM `user` WHERE `key`='".$_POST['key']."')";
		$result = $conn->query($sql);
		$result = mysqli_fetch_row($result);
		if ($result[0]){
			$sql = "SELECT `id`, `name`, `email` FROM `user` WHERE `key`='".$_POST['key']."'";
			$result = mysqli_fetch_row($conn->query($sql));
			$respond['uid'] = $result[0];
			$respond['name'] = $result[1];
			$respond['email']= $result[2];
		}else{
			$respond['err'] = 1;//Данный ключ недействителен
		}
	}else{
		$respond['err'] = 2;//Ключ не был передан серверу
	}
	echo json_encode($respond);
?>