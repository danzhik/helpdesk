<?php 

//первый файл, загружаемый в админке

include_once '../config/db.php'; //подключаем конфигурацию СУБД

session_start(); //создаем/продолжаем сессию


if (isset($_SESSION['user_id'])){ // если есть логированный пользователь, показываем интерфейс
	if ($_SESSION['user_id'] == 1){ // для администратора
		include_once './interfaces/user-interface-admin.php';
	} elseif ($_SESSION['user_id'] > 0) { // для обычного пользователся
		include_once './interfaces/user-interface.php';
	}
} else { // если никто не залогинен, показываем форму для авторизации
	include_once './logging/login.php';
}

