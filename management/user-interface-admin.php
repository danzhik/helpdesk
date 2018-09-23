<?php

//interface for organization (admin)

$user_id = $_SESSION['user_id'];

$all_applications =  $db->query("SELECT * FROM `applications`");
$all_applications_archive =  $db->query("SELECT * FROM `applications_archive`");

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
	<?php if (isset($user_id) && $user_id == 1): ?>
	
	<div class="jumbotron" style="position: absolute; top: 10%; left: 2%; width: 95%;">
		<h3>Пользователь: Администратор</h3>
		<h1 style="text-align: center;">Активные заявки</h1>
		<table>
			<tr>
				<th>ID</th>
				<th>Статус</th>
				<th>Ответсвенное лицо</th>
				<th>Дата создания</th>
				<th>Заказчик</th>
				<th>Адрес</th>
				<th>Контакт</th>
				<th>Тема заявки</th>
				<th>Текст заявки</th>
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

				
				$user = $db->query("SELECT `real_name`, `real_surname` FROM `users` WHERE `id` = $row[assigned_employee]");

				$user = $user->fetch_assoc();

				$user_name = $user['real_name'].' '.$user['real_surname'];

				echo "<tr>
						<td>$row[id]</td>
						<td>$status</td>
						<td>$user_name</td>
						<td>$row[creation_date]</td>
						<td>$row[applicant_name]</td>
						<td>$row[applicant_address]</td>
						<td>$row[applicant_contact]</td>
						<td>$row[application_theme]</td>
						<td>$row[application_text]</td>
					  </tr>";
			}?>
			</tr>
		</table>
		<br>
		<h1 style="text-align: center;">Архив заявок</h1>
		<table>
			<tr>
				<th>ID</th>
				<th>Статус</th>
				<th>Ответсвенное лицо</th>
				<th>Дата создания</th>
				<th>Дата выполнения</th>
				<th>Заказчик</th>
				<th>Адрес</th>
				<th>Контакт</th>
				<th>Тема заявки</th>
				<th>Текст заявки</th>
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

				$user = $db->query("SELECT `real_name`, `real_surname` FROM `users` WHERE `id` = $row[assigned_employee]");

				$user = $user->fetch_assoc();

				$user_name = $user['real_name'].' '.$user['real_surname'];

				echo "<tr>
						<td>$row[id]</td>
						<td>$status</td>
						<td>$user_name</td>
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