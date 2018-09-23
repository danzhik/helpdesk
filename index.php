<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Обращение</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="./assets/js/public-helpdesk.js"></script>
</head>
	<body>
		<div class="jumbotron" style="position: absolute; top: 10%; left: 20%; width: 60%;">
			<h1 style="text-align: center;">Свяжитесь с нами!</h1>
		<form action="./management/application-handler.php" id="application_org">
  			<div class="form-group">
    			
    			<label for="applicant_name">Название организации:</label><input type="text" class="form-control form-control-sm" name="applicant_name" id="applicant_name" required>
    		</div>
    		<div class="form-group">
    			<label for="application_theme">Тема:</label><select class="form-control form-control-sm"  name="application_theme" id="application_theme">
    					<option value="Обрыв кабеля">Обрыв кабеля</option>
    					<option value="Потеря доступа">Потеря доступа</option>
    				  </select>
    		</div>
    		<div class="form-group">		  
    			<label for="application_content">Текст:</label><textarea class="form-control form-control-sm" name="application_content" id="application_content" placeholder="Введите текст заявки" required></textarea>
  			</div>
  			<button type="submit" class="btn btn-primary">Отправить</button>
		</form>
		</div>
	</body>
</html>