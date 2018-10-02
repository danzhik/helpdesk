<?php

// конфигурация соединения с БД

//данные для соединения с базой данных
$dblocation = "127.0.0.1";
$dbname = "helpdesk";
$dbuser = "root";
$dbpasswd = "";

//создание объекта mysqli для работы с БД
$db = new mysqli($dblocation, $dbuser, $dbpasswd, $dbname);

//в случае ошибки соединение, выход из программы и вывод сообщения ошибки
if ($db->connect_error){
    die ('Ошибка соединения с СУБД: '.$db->connect_error);
}

//установка рабочей кодировки для работы с БД
$db->set_charset ('utf8');


//в случае если база пустая (первый запуск программы), выполняем sql файл для добавления таблиц и начальных данных (админ и пара тем заявок)
if ($db->query("SELECT 1 FROM applications LIMIT 1") === false) {
	$script = file_get_contents("helpdesk.sql", FILE_USE_INCLUDE_PATH);
	$db->multi_query($script);

}

