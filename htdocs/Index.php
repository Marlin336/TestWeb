<html>
	<head>
		<meta charset="utf-8">
		<title>Полуденный Никита</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div><button id="reg" onclick="location.href = 'logup.php'">Регистрация</button></div>
		<form id="form">
			<div>Имя: <input required type="text" name="name"/></div>
			<div>Пароль: <input required type="password" id="pass"/></div>
			<input type="hidden" id="passholder" name="pass">			
			<div><input type='submit' value='Войти'/></div>
		</form>
		Или выберите файл-ключ:<br>
		<input id='key_input' name='key' type='file' accept='.key'/>
		<div id='target'></div>
		<script src="js/jquery-3.6.0.js"></script>
		<script src="js/loginscript.js"></script>
		<script src="js/jsencrypt.js"></script>
	</body>
</html>