<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Последние визиты</title>
		<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="/assets/css/common.css" />
		<script type="text/javascript" src="/assets/js/jquery-2.2.4.min.js"></script>
		<script type="text/javascript" src="/assets/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/assets/js/jquery.knob.js"></script>
		<script type="text/javascript" src="/assets/js/common.js"></script>
		<script src="http://chat.svcontact.ru:8008/socket.io/socket.io.js"></script>
	</head>
	<body>
		<div class="container onlines">
		<?php
		foreach ($res as $k => $user) {
			$online = $this->Chat_model->getonline($user['online']);
			$d=date("d"); $m=date("m");	$y=date("Y"); $arrdates='';
			if (date("Y", $user['online'])==$y&&date("m", $user['online'])==$m&&date("d", $user['online'])==$d) {
				$arrdates = 'сегодня';
			} elseif (date("Y", $user['online'])==($y)&&date("m", $user['online'])==$m&&date("d", $user['online'])==$d-1) {
				$arrdates = 'вчера';
			} elseif (date("Y", $user['online'])==($y)&&date("m", $user['online'])==$m&&date("d", $user['online'])==$d-2) {
				$arrdates = 'позавчера';
			} else {
				$arrdates = date("j.m.Y", $user['online']);
			}
			$loged = ($user['id']==1)?' заходил ':' заходила ';
			echo '<div class="onl '.($online?'yes':'no').'">';
			echo $user['name']." ".($online?'в чате':$loged.$arrdates.date(" в H:i", $user['online']));
			echo "</div>";
		}
		?>
		</div>
		<div class="container link"><a href="/"><b>В чатик</b></a></div>
	</body>
</html>