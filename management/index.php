<?php 

//initial file for organization administration

include_once '../config/db.php';

session_start();


if (isset($_SESSION['user_id'])){
	if ($_SESSION['user_id'] == 1){
		include_once './interfaces/user-interface-admin.php';
	} elseif ($_SESSION['user_id'] > 0) {
		include_once './interfaces/user-interface.php';
	}
} else {
	include_once './logging/login.php';
}

