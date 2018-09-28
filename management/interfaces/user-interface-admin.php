<?php

//interface for organization (admin)

$user_id = $_SESSION['user_id'];

if(isset($_POST['updated']) && isset($_POST['app_id'])){

	$app_id = $_POST['app_id'];

	if (isset($_POST['new_app_depart']) && $_POST['new_app_depart'] != '-1'){
		
		$employee_id = $db->query("SELECT `assigned_employee` FROM `applications` WHERE `id` = $app_id");
		$employee_id = $employee_id->fetch_assoc()['assigned_employee'];

		if (!stripos($_POST['new_app_depart'], '_')){

			$db->query("UPDATE `applications` SET assigned_employee = $_POST[new_app_depart] WHERE `id` = $app_id");
			$db->query("UPDATE `users` SET active_applications = active_applications - 1 WHERE `id` = $employee_id");
			$db->query("UPDATE `users` SET active_applications = active_applications + 1 WHERE `id` = $_POST[new_app_depart]");
		} else {
			
			$dep = substr($_POST['new_app_depart'], strpos('new_app_depart', '_')+1);
			
			$sql = $db->query("SELECT `id`, `active_applications` FROM `users` WHERE `user_department` = '$dep'");

			$max = 10000;
			while ($row = $sql->fetch_assoc()){

				if ($row['active_applications'] < $max){
					$max = $row['active_applications'];
					$new_employee_id = $row['id'];
				}
			}

			$db->query("UPDATE `applications` SET assigned_employee = '$new_employee_id' WHERE `id` = $app_id");
			$db->query("UPDATE `users` SET active_applications = active_applications - 1 WHERE `id` = $employee_id");
			$db->query("UPDATE `users` SET active_applications = active_applications + 1 WHERE `id` = $new_employee_id");
		}
	}

	unset($_POST['updated']);
}

if(isset($_POST['updated_arch']) && isset($_POST['arch_app_id'])){

	$arch_app_id = $_POST['arch_app_id'];

	$employee_id = $db->query("SELECT `assigned_employee` FROM `applications_archive` WHERE `id` = $arch_app_id");
	$employee_id = $employee_id->fetch_assoc()['assigned_employee'];

	$db->query("INSERT INTO `applications` SELECT * FROM `applications_archive` WHERE `id` = $arch_app_id");
	$db->query("UPDATE `applications` SET execution_date = '0000-00-00' WHERE `id` = $arch_app_id");
	$db->query("UPDATE `applications` SET application_status = '1' WHERE `id` = $arch_app_id");
	$db->query("DELETE FROM `applications_archive` WHERE `id` = $arch_app_id");
	$db->query("UPDATE `users` SET active_applications = active_applications + 1 WHERE `id` = $employee_id");

	unset($_POST['updated_arch']);
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Управление Заявками</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="./js/management.js"></script>
	<style type="text/css">
		table{
			border: solid;
			border-width: 1px;
			width: 100%;
		}
		th, td{
			border: solid;

			border-width: 1px; 
			padding-left: 10px;
			padding-right: 10px; 
		}
		.nav-tab{
			border-bottom: 1px solid #c0bde7;
    		background: #c0bde7;
    		color: #000;
    		border-bottom-left-radius: 0px;
    		border-bottom-right-radius: 0px;
    	}
    	.nav-tab-active{
    		margin-bottom: -3px;
    		border: 1px solid #298de3;
    		border-bottom: 0px;
    		background: #e9ecef;
    		color: #444;
    	}
    	.nav-tab-wrapper{
    		border-bottom: 1px solid #298de3;
    		margin: 0;
    		padding-top: 9px;
    		padding-bottom: 0;
    	}
    	.inactive{
    		display: none;
    	}
    	.active{
    		padding-top: 1.5rem; 
    	}

    	.btn:focus, .btn:active {
  			outline: none !important;
   			box-shadow: none;
		}	
	</style>
</head>
<body>
	<?php 
		if (isset($user_id) && $user_id == 1): 
			$all_applications =  $db->query("SELECT * FROM `applications`");
			$all_applications_archive =  $db->query("SELECT * FROM `applications_archive`");
			$departments_query = $db->query("SELECT * FROM `departments`");
			$themes_query = $db->query("SELECT `id`, `theme_name` FROM `applications_themes`");

			$themes = [];
			while ($row = $themes_query->fetch_assoc()){
				$themes [$row['id']] = $row['theme_name'];
			} 

			$departments = [];
			while ($row = $departments_query->fetch_assoc()){

				$departments[$row['id']]['name'] = $row['department_name'];
				$users_in_department_query = $db->query("SELECT `id`, `real_name`, `real_surname` FROM `users` WHERE `user_department` = '$row[id]'");

				while($row_user = $users_in_department_query->fetch_assoc()){

					$employee_name = $row_user['real_name'].' '.$row_user['real_surname'];
					$departments[$row['id']]['employees'][$row_user['id']] = $employee_name;
				}
			}
	?>
	
	<div class="jumbotron" style="position: absolute; top: 10%; left: 2%; width: 95%;">

		<h3>Пользователь: Администратор</h3><button class="btn btn-primary" onclick="logout(<?=$user_id?>);">Выйти</button>
		<div class="nav-tab-wrapper">
			<button class="nav-tab btn nav-tab-active" id="applications">Заявки</button>
			<button class="nav-tab btn" id="themes">Темы заявок</button>
           	<button class="nav-tab btn" id="organizations">Организации</button>
           	<button class="nav-tab btn" id="users">Пользователи</button>
           	<button class="nav-tab btn" id="departments">Отделы</button>        
   		</div>
		<div class="active" id="applications_tab">
			<h1 style="text-align: center;">Активные заявки</h1>
			<table>
				<tr>
					<th>ID</th>
					<th>Статус</th>
					<th>Отдел</th>
					<th>Ответсвенное лицо</th>
					<th>Дата создания</th>
					<th>Заказчик</th>
					<th>Адрес</th>
					<th>Контакт</th>
					<th>Тема заявки</th>
					<th>Текст заявки</th>
				</tr>
				<?php

				$applications_ids = [];

			 	while($row = $all_applications->fetch_assoc()) {
			 		$applications_ids[] = $row['id'];

					switch ($row['application_status']) {
						case '0':
							$status = 'Принята';
							break;
						case '1':
							$status = 'В работе';
							break;
						case '2':
							$status = 'На согласовании';
							break;
						case '3':
							$status = 'Решена';
							break;
						default:
							$status = 'В работе';
							break;
					}

				
					$user = $db->query("SELECT `user_department` FROM `users` WHERE `id` = '$row[assigned_employee]'");
					$user = $user->fetch_assoc();

					$user_name = $departments[$user['user_department']]['employees'][$row['assigned_employee']];

					$user_department = $departments[$user['user_department']]['name'];

					$theme = $themes[$row['application_theme']];

					echo "<tr>
							<td>$row[id]</td>
							<td>$status</td>
							<td>$user_department</td>
							<td>$user_name</td>
							<td>$row[creation_date]</td>
							<td>$row[applicant_name]</td>
							<td>$row[applicant_address]</td>
							<td>$row[applicant_contact]</td>
							<td>$theme</td>
							<td>$row[application_text]</td>
						  </tr>";
				}?>
			</table>
			<br>
			<form method="post">
				<div class="form-group">
				<label for="app_id">Заявка №</label>
				<select id="app_id" name="app_id">
					<option>Выбрать</option>
					<?php foreach ($applications_ids as $id) { ?>
						<option value="<?=$id?>"><?=$id?></option>
					<?php } ?>
				</select> :
				</div>
				<div class="form-group">
				<label for="new_app_depart">Перевести на: </label>
				<select id="new_app_depart" name="new_app_depart">
					<option value="-1">Выбрать</option>
					<?php foreach($departments as $id => $department){ ?>
							<optgroup label="<?=$department['name']?>">
								<option value="dep_<?=$id?>"><?=$department['name']?> (любой сотрудник)</option>
								<?php foreach ($department['employees'] as $key => $name) { ?>
									<option value="<?=$key?>"><?=$name?></option>
								<?php } ?>
							</optgroup>
					<?php } ?>
				</select>
				</div>
				<button type="submit" class="btn btn-primary">Внести изменения</button>
				<input type="hidden" name="updated" value="1">
			</form><hr>
			<h1 style="text-align: center;">Архив заявок</h1>
			<table>
				<tr>
					<th>ID</th>
					<th>Статус</th>
					<th>Отдел</th>
					<th>Ответсвенное лицо</th>
					<th>Дата создания</th>
					<th>Дата выполнения</th>
					<th>Заказчик</th>
					<th>Адрес</th>
					<th>Контакт</th>
					<th>Тема заявки</th>
					<th>Текст заявки</th>
				</tr>
				<?php

				$applications_ids = [];

			 	while($row = $all_applications_archive->fetch_assoc()) {
			 		$applications_ids[] = $row['id'];

					switch ($row['application_status']) {
						case '0':
							$status = 'Принята';
							break;
						case '1':
							$status = 'В работе';
							break;
						case '2':
							$status = 'На согласовании';
							break;
						case '3':
							$status = 'Решена';
							break;
						default:
							$status = 'В работе';
							break;
					}

					$user = $db->query("SELECT `user_department` FROM `users` WHERE `id` = '$row[assigned_employee]'");
					$user = $user->fetch_assoc();

					$user_name = $departments[$user['user_department']]['employees'][$row['assigned_employee']];
					$user_department = $departments[$user['user_department']]['name'];

					$theme = $themes[$row['application_theme']];

					echo "<tr>
							<td>$row[id]</td>
							<td>$status</td>
							<td>$user_department</td>
							<td>$user_name</td>
							<td>$row[creation_date]</td>
							<td>$row[execution_date]</td>
							<td>$row[applicant_name]</td>
							<td>$row[applicant_address]</td>
							<td>$row[applicant_contact]</td>
							<td>$theme</td>
							<td>$row[application_text]</td>
						  </tr>";
				}?>
			</table>
			<br>
			<form method="post">
				<div class="form-group">
				<label for="arch_app_id">Заявка №</label>
				<select id="arch_app_id" name="arch_app_id">
					<option>Выбрать</option>
					<?php foreach ($applications_ids as $id) { ?>
						<option value="<?=$id?>"><?=$id?></option>
					<?php } ?>
				</select> :
				</div>
				<input type="hidden" name="updated_arch" value="1">
				<button type="submit" class="btn btn-primary">Вернуть в активные</button>
			</form>
		</div>

		<div class="inactive" id="themes_tab">
			<h1 style="text-align: center;">Темы заявок</h1>
		</div>

		<div class="inactive" id="organizations_tab">
			<h1 style="text-align: center;">Организации</h1>
		</div>

		<div class="inactive" id="users_tab">
			<h1 style="text-align: center;">Пользователи</h1>
		</div>

		<div class="inactive" id="departments_tab">
			<h1 style="text-align: center;">Отделы</h1>
		</div>

		<?php else: ?>
		<h1>Извините, у вас нет доступа к данной странице.</h1>
		<?php endif; ?>
</body>
</html>