<html>
	<head>
		<meta charset="utf-8">
		<title>Регистрация</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<form id="form" enctype="multipart/form-data">
			<div style="background: #A0A0A0;">
				<p>Ответ сервера:
				<p><input type="radio" name='respond' checked value=0>JSON</input>
				<p><input type="radio" name='respond' value=1>XML</input>
			</div>
			<div>Имя: <input required type="text" name="name"/>	</div>
			<div>E-mail: <input required type="text" id="email" name="email"/></div>
			<div>Пароль: <input required type="password" id="pass"/></div>
			<input type="hidden" id="passholder" name="pass">
			<div>Подтв.: <input required type="password" id="pass_valid"/></div>
			<div>
				Фото профиля<br>
				<img id='image' name='photo' width='100px' height='100px'/><br>
				<input id='file_input' name='photo' type='file' accept='image/jpeg' onchange='photo_change()'/>
			</div>
			<div required class="g-recaptcha" data-sitekey="6Ldv0V0dAAAAAAetZPv_5iJwWseLoVBKowxtoWGC"></div>
			<div class="text-danger" id="recaptchaError"></div>
			<div><input id="reg" type='submit' value='Регистрация'/></div>
		</form>
		<div id='msg'></div>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script src="js/jquery-3.6.0.js"></script>
		<script src="js/regscript.js"></script>
		<script src="js/jsencrypt.js"></script>
	</body>
</html>