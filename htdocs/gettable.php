<?php
	session_start();
	$data = $_SESSION;
	$conn = new mysqli('localhost', 'user_'.$data['uid'], $data['pass'], 'testdb') or die("Не удалось подключиться к базе данных"); 
	$response = '';
	if(isset($_POST['slct']) && $_POST['slct'])
		$result = $conn->query("SELECT id, name, email, photo FROM `user` ORDER BY ".$_POST['slct']+1) or die("Не удалось извлечь данные");
	else
		$result = $conn->query("SELECT id, name, email, photo FROM `user`") or die("Не удалось извлечь данные");
	if (mysqli_error($conn)){
		echo mysqli_error($conn);
	}
	while($row = mysqli_fetch_row($result)){
		$response .= '<tr>';
		$response .= '<td>'.$row[1].'</td>';
		$response .= '<td>'.$row[2].'</td>';
		$response .= '<td>';
		if($row[3] == null){
			$image = 'photo/null.jpg';
			$imageData = base64_encode(file_get_contents($image));
			$src = 'data: '.mime_content_type($image).';base64,'.$imageData;
			$response .= '<img src="' . $src . '" hight="100px" width="100px">';
		}
		else{
			$image = 'photo/user_'.$row[0].'.jpg';
			$imageData = base64_encode(file_get_contents($image));
			$src = 'data: '.mime_content_type($image).';base64,'.$imageData;
			$response .= '<img src="' . $src . '" hight="100px" width="100px">';
		}
		$response .= '</td>';
		$response .= '</tr>';
	}
	echo json_encode(array('data' => $response));
?>