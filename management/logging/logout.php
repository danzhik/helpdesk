<?php

//action for logging out

session_start();

/**
 * AJAX Функция для разлогинивания пользователей
 */
function logout () {
	unset($_SESSION['user_id']);
	$response['success'] = true;
	$response['message'] = 'Вы успешно вышли из учетной записи.';
	echo json_encode($response);
}

logout();