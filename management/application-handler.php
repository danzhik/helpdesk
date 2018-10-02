<?php

// исполняемый файл для обработки отправленной формы

include_once '../config/db.php'; //подключение конфигурации СУБД


/**
 * AJAX функция для добавления новой заявки в базу
 */
function add_application(){

	global $db; // глобальная переменная соединения с БД

	if(isset($_POST['applicant_name']) && isset($_POST['application_theme'])){ // выполняем обработку только если указаны имя заявителя и тема заявки

	// сбор данных из запроса
	$applicant_name = htmlspecialchars($db->real_escape_string($_POST['applicant_name']));
	$application_theme = htmlspecialchars($db->real_escape_string($_POST['application_theme']));
	$application_text = htmlspecialchars($db->real_escape_string($_POST['application_content']));
	$creation_date = date ('Y-m-d');
	$application_status = 0;

	//собираем всех пользователей первого отдела
	$sql = $db->query("SELECT `id`, `active_applications` FROM `users` WHERE `user_department` = 1");

	//находим пользователя с наименьшим количеством заявок, и присваиваем его id в переменную $employee_id
	$max = 10000;
	while ($row = $sql->fetch_assoc()){

		if ($row['active_applications'] < $max){
			$max = $row['active_applications'];
			$employee_id = $row['id'];
		}
	}

	// собираем данные по организации (заявителю)
	$sql_org = $db->query("SELECT `organization_address`, `organization_contact` FROM `organizations` WHERE `organization_name` = '$applicant_name'");
    
	if ($sql_org->num_rows > 0) { // если организация уже есть в базу, берем информацию по ней из соответствующей записи

		//собираем данные по организации
		$row = $sql_org->fetch_assoc();
		$address = $row['organization_address'];
		$contact = $row['organization_contact'];

		//составляем запрос с данными по организации и из заявки
		$sql = "INSERT INTO `applications` 
			(application_theme, applicant_name, applicant_address, applicant_contact, application_text, creation_date, application_status, assigned_employee) 
			VALUES ('$application_theme', '$applicant_name', '$address', '$contact',  '$application_text', '$creation_date', $application_status, $employee_id)";
		
	} else { // если организации в базе нет, то создаем запрос без данных организации и добавляем организацию в соответствующую таблицу

		$sql = "INSERT INTO `applications` 
			(application_theme, applicant_name, application_text, creation_date, application_status, assigned_employee) 
			VALUES ('$application_theme', '$applicant_name', '$application_text', '$creation_date', $application_status, $employee_id)";
		$db->query("INSERT INTO `organizations` (organization_name) VALUES ('$applicant_name')");
	}


	$response = []; // инициализируем массив для данных, возвращаемых в JS

	// выполняем запрос и проверяем его успешность
	if ($db->query($sql) == true){ // в случае успеха сообщение об успехе и увеличение счетчика заявок для сотрудника, которому пошла эта заявка
		$response['message'] = 'Ваша заявка отправлена в обработку. Мы свяжемся с вами в ближайшее время!';
		$db->query("UPDATE `users` SET active_applications = active_applications + 1 WHERE `id` = $employee_id");
	} else { // в случае провала сообщение о провале
		$response['message'] = 'Ошибка при обработке вашей заявки...';
	}

	//возвращаем ответ, конвертируя его в JSON
	echo json_encode($response);
	}
}

//добавление заявки
add_application();