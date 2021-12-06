<html>
	<head>
		<title>Основная страница</title>
		<link rel="stylesheet" type="text/css" href="style.css"/>
	</head>
	<body>
		<div>
			<button onclick='edit()'>Изменить</button>
			<button onclick='keyGen()'>Сгенерировать ключ</button> 
			<?php 
				session_start();
				$data = array('name'=>$_SESSION['name'], 'uid'=>$_SESSION['uid']);
				$js_obj = json_encode($data);
				print "<script language='javascript'>var obj=$js_obj;</script>";
			?>

		</div>
		Сортировать по полю:
		<select name='sort' id='sort' onchange='changeSelect();'>
			<option selected disabled hidden>Выберите поле...</option>
			<option value=1>Имя</option>
			<option value=2>E-mail</option>
		</select>
		<table>
		<tr>
			<th>Имя</th>
			<th>E-mail</th>
			<th>Фото</th>
		</tr>
			<tbody id='tab'>
			<?php
				$conn = new mysqli('localhost', 'user_'.$_SESSION['uid'], $_SESSION['pass'], 'testdb') or die("Не удалось подключиться к базе данных"); 
				$result = $conn->query("SELECT id, name, email, photo FROM `user`;") or die("Не удалось извлечь данные");
				if (mysqli_error($conn)){
					echo mysqli_error($conn);
				}
				while($row = mysqli_fetch_row($result)){
					echo '<tr>';
					echo '<td>'.$row[1].'</td>';
					echo '<td>'.$row[2].'</td>';
					if ($row[1] == $data['name']) $data['email'] = $row[2];
					echo '<td>';
					if($row[3] == null)
						$image = 'photo/null.jpg';
					else
						$image = 'photo/user_'.$row[0].'.jpg';
					$src = $_SERVER['HTTP_REFERER'] . $image;
					echo "<img src='$src' hight='100px' width='100px'>";
					echo '</td>';
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
		<script>	
			var d_name = "<?php echo $data['name']; ?>";
			var d_email = "<?php echo $data['email']; ?>";
			var d_uid = "<?php echo $data['uid']; ?>";
		</script>
		<script src='js/mainscript.js'></script>
		<script src="js/jquery-3.6.0.js"></script>
	</body>
</html>


