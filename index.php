<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Обращение</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
	<body>
		<span style="margin: 10rem 10rem 10rem 10rem" class="border border-primary">
		<form action="organization.php">
  			<div class="form-group">
    			
    			<label for="applicant_name">Имя:</label><input type="text" class="form-control form-control-sm" name="applicant_name" id="applicant_name">
    		</div>
    		<div class="form-group">
    			<label for="applicant_address">Aдрес:</label><input type="text" class="form-control form-control-sm" name="applicant_address" id="applicant_address">
    		</div>
    		<div class="form-group">
    			<label for="applicant_contact">Контактный телефон:</label><input type="text" class="form-control form-control-sm" name="applicant_contact" id="applicant_contact">
    		</div>
    		<div class="form-group">
    			<label for="application_theme">Тема:</label><select class="form-control form-control-sm"  name="application_theme" id="application_theme">
    					<option value="1">Обрыв кабеля</option>
    					<option value="1">Потеря доступа</option>
    				  </select>
    		</div>
    		<div class="form-group">		  
    			<label for="application_content">Текст:</label><textarea class="form-control form-control-sm" name="application_content" id="application_content" placeholder="Введите текст заявки" required></textarea>
  			</div>
  			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		</span>
	</body>
</html>