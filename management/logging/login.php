<?php

//interface and functionality for login page

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Авторизация</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="./js/management.js"></script>
</head>
	<body>
		<div class="jumbotron" style="position: absolute; top: 10%; left: 20%; width: 60%;">
			<h1 style="text-align: center;">Введите данные пользователя</h1>
		<form action="./logging/login-handler.php" method="post" id="login_form">
  			<div class="form-group">
    			
    			<label for="user_name">Логин:</label><input type="text" class="form-control form-control-sm" name="user_name" id="user_name" required>
    		</div>

    		<div class="form-group">
    			
    			<label for="user_pass">Пароль:</label><input type="password" class="form-control form-control-sm" name="user_pass" id="user_pass" required>
    		</div>
    		<button type="submit" class="btn btn-primary">Войти</button>
    	</form>
    	</div>
    </body>
</html>