<?php

//interface and functionality for login page
$err_mess = '';
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
	} else {
		$err_mess = 'Incorrect login or password! Please try again.';
	}

	header("Location: ../");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Авторизация</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
	<body>
		<div class="jumbotron" style="position: absolute; top: 10%; left: 20%; width: 60%;">
			<h1 style="text-align: center;">Введите данные пользователя</h1>
		<form action="./login-success.php" method="post">
  			<div class="form-group">
    			
    			<label for="user_name">Логин:</label><input type="text" class="form-control form-control-sm" name="user_name" id="user_name" required>
    		</div>

    		<div class="form-group">
    			
    			<label for="user_pass">Пароль:</label><input type="password" class="form-control form-control-sm" name="user_pass" id="user_pass" required>
    		</div>
    		<input type="hidden" name="updated" value="1">
    		<p style="color: red;"><?=$err_mess?></p>
    		<button type="submit" class="btn btn-primary">Submit</button>
    	</form>
    	</div>
    </body>
</html>