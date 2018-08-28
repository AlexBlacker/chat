<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Чатик</title>
		<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="/assets/css/common.css" />
		<script type="text/javascript" src="/assets/js/jquery-2.2.4.min.js"></script>
		<script type="text/javascript" src="/assets/js/jquery-ui.min.js"></script>
	</head>
	<body>
		<div class="page">
			<div class="login_form">
				<div class="form">
					<form action="/login/auth" method="post">
						<div class="form_item"><input type="password" name="pass" placeholder="Пароль" class="password" /></div>
						<div class="form_item"><button type="submit">Войти</button></div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>