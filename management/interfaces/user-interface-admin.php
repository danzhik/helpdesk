<?php

//interface for organization (admin)

$user_id = $_SESSION['user_id'];

if(isset($_POST['updated_app']) && isset($_POST['app_id'])){

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

	unset($_POST['updated_app']);
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

if(isset($_POST['update_themes'])){

	$data = [];
	if (!empty($_POST['theme_name'])){
		foreach($_POST['theme_name'] as $key=>$value){
			$data[$key]['theme_name'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['theme_solutions'])){
		foreach($_POST['theme_solutions'] as $key=>$value){
			$data[$key]['theme_solutions'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['delete_theme'])){
		foreach($_POST['delete_theme'] as $key=>$value){

			unset($data[$key]);

			$db->query("DELETE FROM `applications_themes` WHERE `id` = $key");
		}
	}

	foreach ($data as $key => $values) {
		$db->query("UPDATE `applications_themes` SET theme_name = '$values[theme_name]', theme_solutions = '$values[theme_solutions]' WHERE `id` = $key");
	}

	unset($_POST['update_themes']);
}

if(isset($_POST['add_theme']) && isset($_POST['theme_name_new'])){
	$new_theme_name = htmlspecialchars($db->real_escape_string($_POST['theme_name_new']));
	$new_theme_solutions = '';
	if (isset($_POST['theme_solutions_new'])){
		$new_theme_solutions = htmlspecialchars($db->real_escape_string($_POST['theme_solutions_new']));
	}
	
	$db->query("INSERT INTO `applications_themes` (theme_name, theme_solutions) VALUES ('$new_theme_name', '$new_theme_solutions')");

	unset($_POST['add_theme']);
}

if(isset($_POST['update_organizations'])){

	$data = [];
	if (!empty($_POST['organization_name'])){
		foreach($_POST['organization_name'] as $key=>$value){
			$data[$key]['organization_name'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['organization_address'])){
		foreach($_POST['organization_address'] as $key=>$value){
			$data[$key]['organization_address'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['organization_contact'])){
		foreach($_POST['organization_contact'] as $key=>$value){
			$data[$key]['organization_contact'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['delete_organization'])){
		foreach($_POST['delete_organization'] as $key=>$value){

			unset($data[$key]);

			$db->query("DELETE FROM `organizations` WHERE `id` = $key");
		}
	}

	foreach ($data as $key => $values) {
		$db->query("UPDATE `organizations` SET organization_address = '$values[organization_address]', organization_contact = '$values[organization_contact]' WHERE `id` = $key");

		$db->query("UPDATE `applications` SET applicant_address = '$values[organization_address]', applicant_contact = '$values[organization_contact]' WHERE `applicant_name` = '$values[organization_name]'");
	}

	unset($_POST['update_organizations']);
}

if(isset($_POST['update_users'])){

	$data = [];
	if (!empty($_POST['user_name'])){
		foreach($_POST['user_name'] as $key=>$value){
			$data[$key]['user_name'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['user_password'])){
		foreach($_POST['user_password'] as $key=>$value){
			if (strlen($value) > 25) {
				$data[$key]['user_password'] = htmlspecialchars($db->real_escape_string($value));
			} else {
				$data[$key]['user_password'] = md5($value);
			}
		}
	}
	if (!empty($_POST['real_name'])){
		foreach($_POST['real_name'] as $key=>$value){
			$data[$key]['real_name'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['real_surname'])){
		foreach($_POST['real_surname'] as $key=>$value){
			$data[$key]['real_surname'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['personal_data'])){
		foreach($_POST['personal_data'] as $key=>$value){
			$data[$key]['personal_data'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['user_department'])){
		foreach($_POST['user_department'] as $key=>$value){
			$data[$key]['user_department'] = htmlspecialchars($db->real_escape_string($value));
		}
	}
	if (!empty($_POST['delete_user'])){
		foreach($_POST['delete_user'] as $key=>$value){

			unset($data[$key]);

			$db->query("DELETE FROM `users` WHERE `id` = $key");
		}
	}

	foreach ($data as $key => $values) {
		$db->query("UPDATE `users` SET user_name = '$values[user_name]', user_password = '$values[user_password]', real_name = '$values[real_name]', real_surname = '$values[real_surname]', personal_data = '$values[personal_data]', user_department = '$values[user_department]' WHERE `id` = $key");
	}

	unset($_POST['update_users']);
}

if (isset($_POST['add_user']) && isset($_POST['user_name_new']) && isset($_POST['user_password_new']) && isset($_POST['user_department_new'])){

	$new_user_name = htmlspecialchars($db->real_escape_string($_POST['user_name_new']));
	$new_user_password = htmlspecialchars($db->real_escape_string($_POST['user_password_new']));
	$new_user_department = htmlspecialchars($db->real_escape_string($_POST['user_department_new']));
	if (isset($_POST['real_name_new'])){
		$new_real_name = htmlspecialchars($db->real_escape_string($_POST['real_name_new']));
	}
	if (isset($_POST['real_surname_new'])){
		$new_real_surname = htmlspecialchars($db->real_escape_string($_POST['real_surname_new']));
	}
	if (isset($_POST['personal_data_new'])){
		$new_personal_data = htmlspecialchars($db->real_escape_string($_POST['personal_data_new']));
	}
	
	
	$db->query("INSERT INTO `users` (user_name, user_password, real_name, real_surname, personal_data, user_department) VALUES ('$new_user_name', '$new_user_password', '$new_real_name', '$new_real_surname', '$new_personal_data', '$new_user_department')");

	unset($_POST['add_user']);
}

if(isset($_POST['update_departments'])){

	$data = [];
	if (!empty($_POST['department_name'])){
		foreach($_POST['department_name'] as $key=>$value){
			$data[$key]['department_name'] = htmlspecialchars($db->real_escape_string($value));
		}
	}

	if (!empty($_POST['delete_department'])){
		foreach($_POST['delete_department'] as $key=>$value){

			unset($data[$key]);

			$db->query("DELETE FROM `departments` WHERE `id` = $key");
		}
	}

	foreach ($data as $key => $values) {
		$db->query("UPDATE `departments` SET department_name = '$values[department_name]' WHERE `id` = $key");
	}

	unset($_POST['update_departments']);
}

if (isset($_POST['add_department']) && isset($_POST['department_name_new'])){

	$new_department_name = htmlspecialchars($db->real_escape_string($_POST['department_name_new']));

	$db->query("INSERT INTO `departments` (department_name) VALUES ('$new_department_name')");

	unset($_POST['add_department']);
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
			$themes_query = $db->query("SELECT * FROM `applications_themes`");
			$organizations_query = $db->query("SELECT * FROM `organizations`");
			$users_query = $db->query("SELECT * FROM `users`");

			$themes = [];
			while ($row = $themes_query->fetch_assoc()){
				$themes [$row['id']]['theme_name'] = $row['theme_name'];
				$themes [$row['id']]['theme_solutions'] = $row['theme_solutions'];
			}

			$organizations = [];
			while ($row = $organizations_query->fetch_assoc()){
				$organizations [$row['id']]['organization_name'] = $row['organization_name'];
				$organizations [$row['id']]['organization_address'] = $row['organization_address'];
				$organizations [$row['id']]['organization_contact'] = $row['organization_contact'];
			} 

			$users = [];
			while ($row = $users_query->fetch_assoc()){
				if ($row['id'] == 1){
					continue;
				}
				$users [$row['id']]['user_name'] = $row['user_name'];
				$users [$row['id']]['user_password'] = $row['user_password'];
				$users [$row['id']]['real_name'] = $row['real_name'];
				$users [$row['id']]['real_surname'] = $row['real_surname'];
				$users [$row['id']]['personal_data'] = $row['personal_data'];
				$users [$row['id']]['user_department'] = $row['user_department'];
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

					$theme = $themes[$row['application_theme']]['theme_name'];

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
				<input type="hidden" name="updated_app" value="1">
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

					$theme = $themes[$row['application_theme']]['theme_name'];

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
			<form method="post">
				<table>
					<tr>
						<th>ID</th>
						<th>Тема</th>
						<th>Способы решения</th>
						<th>Удалить</th>
					</tr>
					<?php foreach ($themes as $key => $values) { ?>
							<tr>
								<td><?=$key?></td>
								<td><input style="width: 100%" type="text" name="theme_name[<?=$key?>]" value="<?=$values['theme_name']?>"></td>
								<td><input style="width: 100%" type="text" name="theme_solutions[<?=$key?>]" value="<?=$values['theme_solutions']?>"></td>
								<td><input type="checkbox" name="delete_theme[<?=$key?>]" value="1"></td>
							</tr>
					<?php	} ?>
				</table>
				<br>
				<input type="hidden" name="update_themes" value="1">
				<button type="submit" class="btn btn-primary">Внести изменения</button>
			</form>
			<hr>
			<h1 style="text-align: center;">Добавить новую тему</h1>
			<form method="post">
				<table>
					<tr>
						<th>Тема</th>
						<th>Способы решения</th>
					</tr>
					<tr>
						<td><input style="width: 100%" type="text" name="theme_name_new"></td>
						<td><input style="width: 100%" type="text" name="theme_solutions_new"></td>
					</tr>
				</table>
				<br>
				<input type="hidden" name="add_theme" value="1">
				<button type="submit" class="btn btn-primary">Добавить</button>
			</form>
		</div>

		<div class="inactive" id="organizations_tab">
			<h1 style="text-align: center;">Организации</h1>
			<form method="post">
				<table>
					<tr>
						<th>ID</th>
						<th>Название</th>
						<th>Адрес</th>
						<th>Контакт</th>
						<td>Удалить</td>
					</tr>
					<?php foreach ($organizations as $key => $values) { ?>
							<tr>
								<td><?=$key?></td>
								<td><input type="hidden" name="organization_name[<?=$key?>]" value="<?=$values['organization_name']?>"><?=$values['organization_name']?></td>
								<td><input style="width: 100%" type="text" name="organization_address[<?=$key?>]" value="<?=$values['organization_address']?>"></td>
								<td><input style="width: 100%" type="text" name="organization_contact[<?=$key?>]" value="<?=$values['organization_contact']?>"></td>
								<td><input type="checkbox" name="delete_organization[<?=$key?>]" value="1"></td>
							</tr>
					<?php	} ?>
				</table>
				<br>
				<input type="hidden" name="update_organizations" value="1">
				<button type="submit" class="btn btn-primary">Внести изменения</button>
			</form>
		</div>

		<div class="inactive" id="users_tab">
			<h1 style="text-align: center;">Пользователи</h1>
			<form method="post">
				<table>
					<tr>
						<th>ID</th>
						<th>Логин</th>
						<th>Пароль</th>
						<th>Имя</th>
						<th>Фамилия</th>
						<th>Данные</th>
						<th>Отдел</th>
						<th>Удалить</th>
					</tr>
					<?php foreach ($users as $key => $values) { ?>
							<tr>
								<td><?=$key?></td>
								<td><input style="width: 100%" type="text" name="user_name[<?=$key?>]" value="<?=$values['user_name']?>"></td>
								<td><input style="width: 100%" type="text" name="user_password[<?=$key?>]" value="<?=$values['user_password']?>"></td>
								<td><input style="width: 100%" type="text" name="real_name[<?=$key?>]" value="<?=$values['real_name']?>"></td>
								<td><input style="width: 100%" type="text" name="real_surname[<?=$key?>]" value="<?=$values['real_surname']?>"></td>
								<td><input style="width: 100%" type="text" name="personal_data[<?=$key?>]" value="<?=$values['personal_data']?>"></td>
								<td>
									<select name="user_department[<?=$key?>]">
										<?php foreach($departments as $id => $department) { ?>
												<option value="<?=$id?>" <?= $values['user_department'] == $id ? 'selected' : '' ?>><?=$department['name']?></option>
										<?php } ?>
									</select>
								</td>
								<td><input type="checkbox" name="delete_user[<?=$key?>]" value="1"></td>
							</tr>
					<?php	} ?>
				</table>
				<br>
				<input type="hidden" name="update_users" value="1">
				<button type="submit" class="btn btn-primary">Внести изменения</button>
			</form>
			<hr>
			<h1 style="text-align: center;">Добавить нового пользователя</h1>
			<form method="post">
				<table>
					<tr>
						<th>Логин</th>
						<th>Пароль</th>
						<th>Имя</th>
						<th>Фамилия</th>
						<th>Данные</th>
						<th>Отдел</th>
					</tr>
					<tr>
						<td><input style="width: 100%" type="text" name="user_name_new"></td>
						<td><input style="width: 100%" type="text" name="user_password_new"></td>
						<td><input style="width: 100%" type="text" name="real_name_new"></td>
						<td><input style="width: 100%" type="text" name="real_surname_new"></td>
						<td><input style="width: 100%" type="text" name="personal_data_new"></td>
						<td>
							<select name="user_department_new">
								<?php foreach($departments as $id => $department) { ?>
										<option value="<?=$id?>"><?=$department['name']?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
				</table>
				<br>
				<input type="hidden" name="add_user" value="1">
				<button type="submit" class="btn btn-primary">Добавить</button>
			</form>
		</div>

		<div class="inactive" id="departments_tab">
			<h1 style="text-align: center;">Отделы</h1>
			<form method="post">
				<table>
					<tr>
						<th>ID</th>
						<th>Название</th>
						<th>Удалить</th>
					</tr>
					<?php foreach ($departments as $key => $values) { ?>
							<tr>
								<td><?=$key?></td>
								<td><input style="width: 100%" type="text" name="department_name[<?=$key?>]" value="<?=$values['name']?>"></td>
								<td><input type="checkbox" name="delete_department[<?=$key?>]" value="1"></td>
							</tr>
					<?php	} ?>
				</table>
				<br>
				<input type="hidden" name="update_departments" value="1">
				<button type="submit" class="btn btn-primary">Внести изменения</button>
			</form>
			<hr>
			<h1 style="text-align: center;">Добавить новый отдел</h1>
			<form method="post">
				<div class="form-group">
    			
    				<label for="department_name_new">Название:</label><input type="text" class="form-control form-control-sm" name="department_name_new" id="department_name_new" style="width: 30%">
    			</div>	
				<input type="hidden" name="add_department" value="1">
				<button type="submit" class="btn btn-primary">Добавить</button>
			</form>	
		</div>

		<?php else: ?>
		<h1>Извините, у вас нет доступа к данной странице.</h1>
		<?php endif; ?>
</body>
</html>