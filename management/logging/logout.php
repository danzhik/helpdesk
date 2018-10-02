<?php

//исполняемый файл дял выхода из учетной записи

session_start();

/**
 * AJAX функция для разлогинивания пользователей
 */
function logout () {
	//удаляем сессионную переменную пользователя
	unset($_SESSION['user_id']);

	//формируем ответ для JS
	$response['success'] = true;
	$response['message'] = 'Вы успешно вышли из учетной записи.';

	//возвращаем ответ в JSON формате
	echo json_encode($response);
}

//выйти из учетной записи
logout();