<?php

// handling logging in attempts
include_once '../config/db.php';
session_start();

/**
 * AJAX function fired when management logging in is performed
 */
function attempt_login(){
	global $db;
	
	if(isset($_POST['user_name']) && isset($_POST['user_pass'])){
	
	$user_name = htmlspecialchars($db->real_escape_string($_POST['user_name']));
	$user_pass = md5($_POST['user_pass']);
	$sql = $db->query("SELECT `id`
					FROM `users` 
					WHERE `user_name` ='$user_name'
					AND `user_password` = '$user_pass'");

	if ($sql->num_rows > 0){
		$row = $sql->fetch_assoc();
		$_SESSION['user_id'] = $row['id'];
		$response['success'] = true;
		$response['message'] = 'You logged in successfully!';
	} else {
		$response['success'] = false;
		$response['message'] = 'Incorrect login or password! Please try again.';
	}

	echo json_encode($response);
	}
}

attempt_login();