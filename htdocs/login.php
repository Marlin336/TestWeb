<?php
// Для безопасной передачи пароля используется RSA шифрование
// На сервере - закрытый ключ
	$prk = '-----BEGIN RSA PRIVATE KEY-----
MIIBOgIBAAJBAMb4GRVeQA3TnT1BMbEakJNS+MIFQQAlkHOeGNwl+AECSJoFUdlE
TD4nc4o4/g/7ZlYwLedWvnN4XotrLcY/gdkCAwEAAQJAcbyq4gshhIXeEYDt/fZY
hM7eQlKsiH/wphbO0TGnJjN87SVMdDBBq1MY5WF7n7MpBlhBOVtdr9Lp6JlVZrcq
rQIhAOT7NKT3hJz/7QaMojxeSWHBUZLjQ05HYNuRtpQ3EKu3AiEA3nJSPw8MpnOL
SLHwwmkOxsbXIfp4kMQsDf9jozpuXu8CIDryLBg3m8zq2TKxa2ZNA2kF4iEFHiN9
noKHnU/EaSc7AiBpa76EugohvHKPEh1q1UU2eTetl7mZyg6i150N13xFKwIhALDu
cVvvcl3yPcUQEg9A6NUTd9ryF8F/+9iIDAxiKRrc
-----END RSA PRIVATE KEY-----';
	$pass = $_POST['pass'];
	$ogp = openssl_get_privatekey($prk);
	openssl_private_decrypt(base64_decode($pass), $out, $ogp);
	$user = 'guest';
	$password = '';
	$db = 'testdb';
	
	$conn = new mysqli('localhost', $user, $password, $db);//Не удалось подключиться к базе данных
	$qr = $conn->query("SELECT EXISTS (SELECT * FROM `user` WHERE `name`='".$_POST['name']."')");
	$t = mysqli_fetch_row($qr)[0];
	if ($t){
		$uid = $conn->query("SELECT `id` FROM `user` WHERE `name` = '".$_POST['name']."'");
		$uid = mysqli_fetch_row($uid)[0];
		
		$conn->close();
		$user = 'user_'.$uid;
		$conn = @new mysqli('localhost', $user, $out, $db);
		if (mysqli_connect_error()){
			echo 3; //Пароль не действителен
		}
		else{
			session_start();
			$_SESSION = $_POST;
			$_SESSION['pass'] = $out;
			$_SESSION['uid'] = $uid;
			echo 0;
		}

	}
	else{
		echo 2;//Пользователь с таким именем не найден
	}
?>