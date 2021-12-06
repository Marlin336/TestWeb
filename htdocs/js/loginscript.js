$('#form').submit(function(e){
	e.preventDefault();
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
		type: "POST",
		url: "login.php",
		data: $(this).serialize(),
		success: function(resp){
			if (resp == 0){
				document.location.href = "mainpage.php";
			}
			else if (resp == 1){
				alert("Не удалось подключиться к базе данных");
			}
			else if (resp == 2){
				alert("Пользователь с таким именем не найден");
			}
			else if (resp == 3){
				alert("Пароль не действителен");
			}
			else{
				alert("Что-то пошло не так...");
			}
		}
	});
});

$('#key_input').change(function(e){
	let file = $(this).prop('files')[0];
	let reader = new FileReader();
	reader.readAsText(file);
	reader.onload = function() {
		$.ajax({
			url: 'getkey.php',
			type: "POST",
			data: {key: reader.result},
			success: function(resp){
				resp = JSON.parse(resp);
				if(resp['err'] == 0)
					edit(resp['uid'], resp['name'], resp['email']);
				else if(resp['err'] == 1)
					alert("Данный ключ недействителен");
				else if(resp['err'] == 2)
					alert("Ключ не был передан серверу");
				else
					alert("Что-то пошло не так");
			},
			error: function(err){
				$('#target').html("Ошибка передачи ключа серверу");
			}
		});
	}
	reader.onerror = function() {
		$('#target').html("Ошибка чтения ключа");
	};
	$('#key_input').val('');
});


function edit(in_id, in_name, in_email){
	var new_name = prompt("Введите новое имя", in_name);
	if (new_name && new_name != in_name && new_name.length != 0){
		alert("Имя изменено");
	}
	else{
		new_name = in_name;
	}
	var new_email = prompt("Введите новый e-mail", in_email);
	if (new_email && new_email != in_email && new_email.length != 0){
		alert("E-mail изменён");
	}
	else{
		new_email = in_email;
	}
	$.ajax({
		url: "edit.php",
		type: "POST",
		data: {name: new_name, email:new_email, id: in_id},
		error: function(err){
			alert(err);
		}
	});
}
