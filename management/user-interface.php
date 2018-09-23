<?php

//interface for organization

$user_id = $_SESSION['user_id'];

if(isset($_POST['updated'])){

	$app_id = $_POST['app_id'];

	if ($_POST['new_app_status'] != -1){
		$db->query("UPDATE `applications` SET application_status = $_POST[new_app_status] WHERE `id` = $app_id");
		if ($_POST['new_app_status'] == 3){
			$db->query("INSERT INTO `applications_archive` SELECT * FROM `applications` WHERE `id` = $app_id");
			$db->query("UPDATE `applications_archive` SET execution_date = '".date('Y-m-d')."' WHERE `id` = $app_id");
			$db->query("DELETE FROM `applications` WHERE `id` = $app_id");
		}
	}
}

$user_applications =  $db->query("SELECT * FROM `applications` WHERE `assigned_employee` = '$user_id'");
$user_applications_archive =  $db->query("SELECT * FROM `applications_archive` WHERE `assigned_employee` = '$user_id'");

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
	$user = $db->query("SELECT `real_name`, `real_surname` FROM `users` WHERE `id` = $user_id");

	$user = $user->fetch_assoc();

	$user_name = $user['real_name'].' '.$user['real_surname'];?>
	<div class="jumbotron" style="position: absolute; top: 10%; left: 2%; width: 95%;">
		<h3>Пользователь: <?=$user_name?></h3>
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

				switch ($row['application_theme']) {
					case 'Обрыв кабеля':
						$string = 'Направить ремонтную бригаду.';
						break;
					case 'Потеря доступа':
						$string = 'Сообщить в службу поддержки';
					default:
						$string = '-';
						break;
				}

				echo "<tr>
						<td>$row[id]</td>
						<td>$status</td>
						<td>$row[creation_date]</td>
						<td>$row[applicant_name]</td>
						<td>$row[applicant_address]</td>
						<td>$row[applicant_contact]</td>
						<td>$row[application_theme]</td>
						<td>$row[application_text]</td>
						<td>$string</td>
					  </tr>";
			}?>
			</tr>
		</table>
		<br>
		<form method="post">
			<div class="form-group">
			<label for="app_id">Заявка №</label>
			<select id="app_id" name="app_id">
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
			<label for="new_app_depart">Перевести на отдел: </label>
			<select id="new_app_depart" name="new_app_depart">
				<option value="-1">Выбрать</option>
				<option value="1">Первый отдел</option>
				<option value="2">Второй отдел</option>
				<option value="3">Третий отдел</option>
			</select>
			</div>
			<button type="submit" class="btn btn-primary">Внести изменения</button>
			<input type="hidden" name="updated" value="1">
		</form>
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

				echo "<tr>
						<td>$row[id]</td>
						<td>$status</td>
						<td>$row[creation_date]</td>
						<td>$row[execution_date]</td>
						<td>$row[applicant_name]</td>
						<td>$row[applicant_address]</td>
						<td>$row[applicant_contact]</td>
						<td>$row[application_theme]</td>
						<td>$row[application_text]</td>
					  </tr>";
			}?>
			</tr>
		</table>
	</div>
	<?php else: ?>
	<h1>Извините, у вас нет доступа к данной странице.</h1>
	<?php endif; ?>
</body>
</html>