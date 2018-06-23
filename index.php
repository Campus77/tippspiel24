<?php
/*
This file is part of tippspiel24.

tippspiel24 is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
 
tippspiel24 is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with tippspiel24.  If not, see <http://www.gnu.org/licenses/>.
*/

include ('regtoken.php');

$errors = array(
	"reg_user_exists" => "Der Benutzername ist bereits vergeben, bitte wähle einen anderen aus.",
	"reg_pw_mismatch" => "Die Passwörter stimmen nicht überein.",
	"reg_db_error"    => "Datenbank-Fehler. Bitte Admins kontaktieren."
);

?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="author" content="Marcel Daneyko">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link href="css/competition.css" rel="stylesheet" type="text/css" />
		<link href='fonts/roboto.css' rel="stylesheet" type="text/css" />
		<script src="js/jquery-3.3.1.min.js"></script>
		<script>
			$(document).ready(function() {
				$('input[name="username"]').focus();
			});
		</script>
		<title>WM 2018</title>
	</head>

	<body id="login_body">
		<header>
		</header>
		<section id="content" class="center">
		</section>

<?php
	$error = isset($_GET['error']) ? $_GET['error'] : FALSE;
	$errorText = "";
	if ($error && array_key_exists($error, $errors)) {
		$errorText = "<div class=\"message error\">" . $errors[$error] . "</div>";
	}

	if (isset($_GET['register']) && $_GET['register'] == $regToken) {
		echo <<< EOT
		 <div class="wm-form">
			<h1>Deine Daten</h1>
			$errorText
			<form action="modules/user_register.php" method="post">
				<input type="hidden" name="regtoken" value="{$_GET['register']}" />
				<input type="text" name="username" placeholder="Benutzername" required />
				<input type="password" name="password" placeholder="Passwort" required />
				<input type="password" name="password2" placeholder="Nochmal Passwort" required />
				<div style="width: 100%; height:40px"><input type="submit" value="registrieren" /></div>
			</form>
		</div>		
EOT;
	}
	else if (isset($_GET['resetpw'])) {
		echo "<div class=\"wm-form\">
				<h1>Passwort zurücksetzen</h1>
				$errorText
				<form action=\"modules/user_resetpw.php\" method=\"post\">
					<input type=\"hidden\" name=\"resetpw\" value=\"{$_GET['resetpw']}\"/>
					<input type=\"password\" name=\"password\" placeholder=\"Neues Passwort\" required />
					<input type=\"password\" name=\"password2\" placeholder=\"Nochmal neues Passwort\" required />
					<div style=\"width: 100%; height:40px\"><input type=\"submit\" value=\"zurücksetzen\" /></div>
				</form>
			</div>";
	}
	else {
		echo <<< EOT
		<div class="wm-form">
			<h1>Login</h1>
			<form action="modules/user_login.php" method="post">
				<input type="text" name="username" placeholder="Benutzername" required />
				<input type="password" name="password" placeholder="Passwort" required />
				<div style="width: 100%; height:40px"><input type="submit" value="anmelden" /></div>
			</form>
		</div>
EOT;
	}
?>
	</body>
</html>