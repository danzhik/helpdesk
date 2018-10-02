<?php

// исполняемый файл для проверки данных авторизуемого пользователя

include_once '../../config/db.php'; // подключение конйигурации СУБД

session_start(); // продолжение сессии

/**
 * AJAX функция обработки запроса на вход в систему
 */
function attempt_login(){

	global $db; //глобальная переменная связи с СУБД
	
	if(isset($_POST['user_name']) && isset($_POST['user_pass'])){ //выполнить код только если были указаны логин и пароль
	
	//собираем данные из формы, пароли закодированы через MD5 стандарт
	$user_name = htmlspecialchars($db->real_escape_string($_POST['user_name']));
	$user_pass = md5($_POST['user_pass']);

	//проверяем существует ли такой пользователь и верен ли пароль
	$sql = $db->query("SELECT `id`
					FROM `users` 
					WHERE `user_name` ='$user_name'
					AND `user_password` = '$user_pass'");

	//проверка запроса
	if ($sql->num_rows > 0){ //если данные верны
		//выводим данные пользователя в ассоциативный массив
		$row = $sql->fetch_assoc();

		//создаем сессионную переменную с id пользователя
		$_SESSION['user_id'] = $row['id'];

		//возврашаем успешный ответ
		$response['success'] = true;
		$response['message'] = 'Авторизация прошла успешно!';
	} else { // возвращаем негативный ответ
		$response['success'] = false;
		$response['message'] = 'Неправильный логин или пароль! Введите верные данные.';
	}

	//возвращаем ответ в формате JSON
	echo json_encode($response);
	}
}

//выполнить вход в систему
attempt_login();