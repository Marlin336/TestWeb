$('#form').submit(function(e){
	e.preventDefault();
	if (!($('input[name="email"]').val().match('^.+@.+\..+$'))){
		alert('Поле E-mail имеет неверный формат!');
	}
	else if ($('#pass').val() != $('#pass_valid').val()){
		//e.preventDefault();
		alert("Пароли не совпадают!");
	}
	else
	{
		var crypt = new JSEncrypt();
		// Для безопасной передачи пароля используется RSA шифрование
		// На клиенте - открытый ключ
		var pubk = '-----BEGIN PUBLIC KEY-----\
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAMb4GRVeQA3TnT1BMbEakJNS+MIFQQAl\
kHOeGNwl+AECSJoFUdlETD4nc4o4/g/7ZlYwLedWvnN4XotrLcY/gdkCAwEAAQ==\
-----END PUBLIC KEY-----';
		crypt.setPublicKey(pubk);
		var endata = crypt.encrypt($('#pass').val());
		$('#passholder').val(endata);
		$.ajax({
			url: 'reg.php',
			type: 'POST',
			data: $(this).serialize(),
			success: function(resp){
				if ($('input[name="respond"]:checked').val() == 0){
					resp = JSON.parse(resp);
					if(resp['err'] == 1){
						alert("Не удалось подключиться к базе данных");
					}
					else if(resp['err'] == 2){
						alert("Пользователь с таким именем или email зарегистрирован");
					}
					else if(resp['err'] == 3){
						alert("Код капчи не прошёл проверку на сервере");
					}
					else if (resp['err'] == 4){
						alert("Не удалось получить код капчи");
					}
					else{
						add_photo(resp['id']);
						alert("Регистрация прошла успешно!");
						window.history.back();
					}
				}
				else{
					parser = new DOMParser();
					resp = parser.parseFromString(resp, "text/xml");
					if(resp.querySelector("err").innerHTML == 1){
						alert("Не удалось подключиться к базе данных");
					}
					else if(resp.querySelector("err").innerHTML == 2){
						alert("Пользователь с таким именем или email зарегистрирован");
					}
					else if(resp.querySelector("err").innerHTML == 3){
						alert("Код капчи не прошёл проверку на сервере");
					}
					else if (resp.querySelector("err").innerHTML == 4){
						alert("Не удалось получить код капчи");
					}
					else{
						add_photo(resp.querySelector("id").innerHTML);
						alert("Регистрация прошла успешно!");
						window.history.back();
					}
				}
			},
			error: function(err){
				$('#msg').html(err);
			}
		});
		grecaptcha.reset();
	}
});

function add_photo(id){
	var file = $('#file_input').prop('files')[0];
	var img_elem = $('#image');
	if (img_elem.attr('src')){
		var s_data = new FormData();
		s_data.append('photo',file);
		s_data.append('uid', id);
		$.ajax({
			type: 'POST',
			url: 'attachimg.php',
			data: s_data,
			cache: false,
			contentType: false,
			processData: false,
			error: function(err){
				$('#msg').html("Не удалось прикрепить фото: "+err);
			}
		});
	}
}




function photo_change(){
	var input = $('#file_input').prop('files')[0];
	//$('img').val(input);
    if (input.type.match('image.*')) {
        var fr = new FileReader();
        fr.onload = function (e) { $("#image").attr("src", fr.result); }
        fr.readAsDataURL(input);
    }
}

// Работа с виджетом recaptcha
// 1. Получить ответ гугл капчи
var captcha = grecaptcha.getResponse();

// 2. Если ответ пустой, то выводим сообщение о том, что пользователь не прошёл тест.
// Такую форму не будем отправлять на сервер.
if (!captcha.length) {
  // Выводим сообщение об ошибке
  $('#recaptchaError').text('* Вы не прошли проверку "Я не робот"');
} else {
  // получаем элемент, содержащий капчу
  $('#recaptchaError').text('');
}

// 3. Если форма валидна и длина капчи не равно пустой строке, то отправляем форму на сервер (AJAX)
if ((formValid) && (captcha.length)) {
  // добавить в formData значение 'g-recaptcha-response'=значение_recaptcha
  formData.append('g-recaptcha-response', captcha);
}

// 4. Если сервер вернул ответ error, то делаем следующее...
// Сбрасываем виджет reCaptcha
grecaptcha.reset();
// Если существует свойство msg у объекта $data, то...
if ($data.msg) {
  // вывести её в элемент у которого id=recaptchaError
  $('#recaptchaError').text($data.msg);
}