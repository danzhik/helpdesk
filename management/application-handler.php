<?php

// database interactions, fired after sent of the form

include_once '../config/db.php';


/**
 * AJAX function fired when user sents application form
 */
function add_application(){

	global $db;

	if(isset($_POST['applicant_name']) && isset($_POST['application_theme'])){

	$applicant_name = htmlspecialchars($db->real_escape_string($_POST['applicant_name']));
	$application_theme = htmlspecialchars($db->real_escape_string($_POST['application_theme']));
	$application_text = htmlspecialchars($db->real_escape_string($_POST['application_content']));
	$creation_date = date ('Y-m-d');
	$application_status = 0;

	$sql = $db->query("SELECT `id`, `active_applications` FROM `users` WHERE `user_department` = 1");

	$max = 10000;
	while ($row = $sql->fetch_assoc()){

		if ($row['active_applications'] < $max){
			$max = $row['active_applications'];
			$employee_id = $row['id'];
		}
	}

	$sql_org = $db->query("SELECT `organization_address`, `organization_contact` FROM `organizations` WHERE `organization_name` = '$applicant_name'");
    
	if ($sql_org->num_rows > 0) {

		$row = $sql_org->fetch_assoc();
		$address = $row['organization_address'];
		$contact = $row['organization_contact'];

		$sql = "INSERT INTO `applications` 
			(application_theme, applicant_name, applicant_address, applicant_contact, application_text, creation_date, application_status, assigned_employee) 
			VALUES ('$application_theme', '$applicant_name', '$address', '$contact',  '$application_text', '$creation_date', $application_status, $employee_id)";
		
	} else {

		$sql = "INSERT INTO `applications` 
			(application_theme, applicant_name, application_text, creation_date, application_status, assigned_employee) 
			VALUES ('$application_theme', '$applicant_name', '$application_text', '$creation_date', $application_status, $employee_id)";
		$db->query("INSERT INTO `organizations` (organization_name) VALUES ('$applicant_name')");
	}


	$response = [];

	if ($db->query($sql) == true){
		$response['message'] = 'Ваша заявка отправлена в обработку. Мы свяжемся с вами в ближайшее время!';
		$db->query("UPDATE `users` SET active_applications = active_applications + 1 WHERE `id` = $employee_id");
	} else {
		$response['message'] = 'Ошибка при обработке вашей заявки...';
	}

	echo json_encode($response);
	}
}

add_application();