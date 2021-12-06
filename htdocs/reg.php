<?php

$respond = array('id'=>null, 'err'=>0);
// секретный ключ
$secret = '6Ldv0V0dAAAAAHTtaRq30qCnfCodT7H-NUq4SOFg';
require_once (dirname(__FILE__).'/recaptcha/autoload.php');
if (isset($_POST['g-recaptcha-response'])) {
	// создать экземпляр службы recaptcha, используя секретный ключ
	$recaptcha = new \ReCaptcha\ReCaptcha($secret);
	// получить результат проверки кода recaptcha
	$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	// если результат положительный, то...
	if ($resp->isSuccess()){
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
	
	$conn = new mysqli('localhost', $user, $password, $db) or $respond['err'] = 1;//не удалось подключиться

	$in_name = $conn->real_escape_string($_POST['name']);
	$in_pass = $conn->real_escape_string($out);
	$in_email = $conn->real_escape_string($_POST['email']);
	$sql = "CALL add_user('$in_name', '$in_pass', '$in_email')";
	$result = $conn->query($sql) or $respond['err'] = 2;//пользователь с таким именем или email зарегистрирован
	if ($result != false){
		$user_id = mysqli_fetch_row($result)[0];
		while($conn->next_result()) $conn->store_result();
		$respond['id'] = $user_id;
	}
	$conn->close();
  } else {
    $errors = $resp->getErrorCodes();
    $data['error-captcha']=$errors;
    $data['msg']='Код капчи не прошёл проверку на сервере';
    $data['result']='error';
	$respond['err'] = 3;//Код капчи не прошёл проверку на сервере
  }

} else {
  $data['result']='error';
  die("Не удалось получить код капчи");
  $respond['err'] = 4;//Не удалось получить код капчи
}
if($_POST['respond'] == 0){
	echo json_encode($respond);
}
else{
	require "arrayToXml.php";
	echo arrayToXml::toXml($respond);
}

?>