<?php

// database interactions, fired after sent of the form

include_once '../config/db.php';


/**
 * AJAX function fired when user sents application form
 */
function add_application(){

	global $db;

	$response = [];


	

	echo json_encode($response);
}

add_application();