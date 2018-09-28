<?php

//interface for organization

$user_id = $_SESSION['user_id'];

if(isset($_POST['updated']) && isset($_POST['app_id'])){

	$app_id = $_POST['app_id'];

	if (isset($_POST['new_app_status']) && $_POST['new_app_status'] != '-1'){
		$db->query("UPDATE `applications` SET application_status = $_POST[new_app_status] WHERE `id` = $app_id");
		if ($_POST['new_app_status'] == 3){
			$db->query("INSERT INTO `applications_archive` SELECT * FROM `applications` WHERE `id` = $app_id");
			$db->query("UPDATE `applications_archive` SET execution_date = '".date('Y-m-d')."' WHERE `id` = $app_id");
			$db->query("DELETE FROM `applications` WHERE `id` = $app_id");
			$db->query("UPDATE `users` SET active_applications = active_applications - 1 WHERE `id` = $user_id");
		}
	}

	if (isset($_POST['new_app_depart']) && $_POST['new_app_depart'] != '-1'){
		
		if (!stripos($_POST['new_app_depart'], '_')){

			$db->query("UPDATE `applications` SET assigned_employee = $_POST[new_app_depart] WHERE `id` = $app_id");
			$db->query("UPDATE `users` SET active_applications = active_applications - 1 WHERE `id` = $user_id");
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
			$db->query("UPDATE `users` SET active_applications = active_applications - 1 WHERE `id` = $user_id");
			$db->query("UPDATE `users` SET active_applications = active_applications + 1 WHERE `id` = $new_employee_id");
		}
	}

	unset($_POST['updated']);
}

if(isset($_POST['updated_arch']) && isset($_POST['arch_app_id'])){

	$arch_app_id = $_POST['arch_app_id'];

	$db->query("INSERT INTO `applications` SELECT * FROM `applications_archive` WHERE `id` = $arch_app_id");
	$db->query("UPDATE `applications` SET execution_date = '0000-00-00' WHERE `id` = $arch_app_id");
	$db->query("UPDATE `applications` SET application_status = '1' WHERE `id` = $arch_app_id");
	$db->query("DELETE FROM `applications_archive` WHERE `id` = $arch_app_id");
	$db->query("UPDATE `users` SET active_applications = active_applications + 1 WHERE `id` = $user_id");

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
	</style>
</head>
<body>
	<?php if (isset($user_id)): 

	$user_applications =  $db->query("SELECT * FROM `applications` WHERE `assigned_employee` = '$user_id'");
	$user_applications_archive =  $db->query("SELECT * FROM `applications_archive` WHERE `assigned_employee` = '$user_id'");
	$departments_query = $db->query("SELECT * FROM `departments`");
	$themes_query = $db->query("SELECT * FROM `applications_themes`");

	$user = $db->query("SELECT `real_name`, `real_surname` FROM `users` WHERE `id` = $user_id");
	$user_department = $db->query("SELECT `department_name` FROM `departments` WHERE `id` IN (SELECT `user_department` FROM `users` WHERE `id` = $user_id)");

	$user = $user->fetch_assoc();
	$user_department = $user_department->fetch_assoc()['department_name'];

	$user_name = $user['real_name'].' '.$user['real_surname'];

	$themes = [];
	while ($row = $themes_query->fetch_assoc()){
		$themes [$row['id']]['theme_name'] = $row['theme_name'];
		$themes [$row['id']]['theme_solutions'] = $row['theme_solutions'];
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
		<h3>Пользователь: <?=$user_name?></h3>
		<h3>Отдел: <?=$user_department?></h3><button class="btn btn-primary" onclick="logout(<?=$user_id?>);">Выйти</button>
		<h1 style="text-align: center;">Ваши заявки</h1>
		<table>
			<tr>
				<th>ID</th>
				<th>Статус</th>
				<th>Дата создания</th>
				<th>Заказчик</th>
				<th>Адрес</th>
				<th>Контакт</th>
				<th>Тема заявки</th>
				<th>Текст заявки</th>
				<th>Способ решения</th>
			</tr>
			<?php

			$applications_ids = [];

			 while($row = $user_applications->fetch_assoc()) {
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

				$theme = $themes[$row['application_theme']]['theme_name'];
				$solutions = $themes[$row['application_theme']]['theme_solutions'];

				echo "<tr>
						<td>$row[id]</td>
						<td>$status</td>
						<td>$row[creation_date]</td>
						<td>$row[applicant_name]</td>
						<td>$row[applicant_address]</td>
						<td>$row[applicant_contact]</td>
						<td>$theme</td>
						<td>$row[application_text]</td>
						<td>$solutions</td>
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
			<label for="new_app_status">Изменить статус на: </label>
			<select id="new_app_status" name="new_app_status">
				<option value="-1">Выбрать</option>
				<option value="1">В работе</option>
				<option value="2">На согласовании</option>
				<option value="3">Решена</option>
			</select>
			</div>
			<div class="form-group">
			<label for="new_app_depart">Перевести на: </label>
			<select id="new_app_depart" name="new_app_depart">
				<option value="-1">Выбрать</option>
				<?php foreach($departments as $id => $department){
						if ($user_department == $department['name']){
							continue;
						} ?>
						<optgroup label="<?=$department['name']?>">
							<option value="dep_<?=$id?>"><?=$department['name']?> (любой сотрудник)</option>
							<?php foreach ($department['employees'] as $key => $name) { ?>
								<option value="<?=$key?>"><?=$name?></option>
							<?php } ?>
						</optgroup>
				<?php } ?>
			</select>
			</div>
			<input type="hidden" name="updated" value="1">
			<button type="submit" class="btn btn-primary">Внести изменения</button>
		</form><hr>
		<h1 style="text-align: center;">Архив заявок</h1>
		<table>
			<tr>
				<th>ID</th>
				<th>Статус</th>
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

			 while($row = $user_applications_archive->fetch_assoc()) {
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

				$theme = $themes[$row['application_theme']]['theme_name'];

				echo "<tr>
						<td>$row[id]</td>
						<td>$status</td>
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
	<?php else: ?>
	<h1>Извините, у вас нет доступа к данной странице.</h1>
	<?php endif; ?>
</body>
</html>