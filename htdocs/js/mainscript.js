function changeSelect(){
	var sort_sel = $('#sort').val();
	$.ajax({
		type: "POST",
        url: "gettable.php",
        data: {slct:sort_sel},
		success: function(resp)
		{
			try{
				var json = JSON.parse(resp);
				$('#tab').html(json.data);
			} catch(ex){
				alert(ex);
			}
		},
		error: function(err)
		{
			alert('Error');
		}
	});
}

function keyGen(){
	$.ajax({
		type: "POST",
		url: 'keygen.php',
		data: obj,
		success: function(resp){
			var hiddenElement = document.createElement('a');
			hiddenElement.href = 'data:attachment/bin,' + encodeURI(resp);
			hiddenElement.target = '_blank';
			hiddenElement.download = d_name+'.key';
			hiddenElement.click();
		}
	});
}

function edit(){
	var new_name = prompt("Введите новое имя", d_name);
	if (new_name && new_name != d_name && new_name.length != 0){
		alert("Имя изменено");
	}
	else{
		new_name = d_name;
	}
	var new_email = prompt("Введите новый e-mail", d_email);
	if (new_email && new_email != d_email && new_email.length != 0){
		alert("E-mail изменён");
	}
	else{
		new_email = d_email;
	}
	$.ajax({
		url: "edit.php",
		type: "POST",
		data: {name: new_name, email:new_email, id: d_uid},
		success: function(resp){
			changeSelect();
			d_name = new_name;
			d_email = new_email;
		},
		error: function(err){
			alert(err);
		}
	});
}