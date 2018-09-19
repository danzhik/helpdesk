<?php 

//initial file for organization administration

include_once '../config/db.php';

session_start();


if (isset($_SESSION['user_id'])){
	include_once './user-interface.php';
} else {
	include_once './login.php';
}

