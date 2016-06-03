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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="author" content="Marcel Daneyko">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link href="css/competition.css" rel="stylesheet prefetch" type="text/css" />
		<link href='fonts/roboto.css' rel="stylesheet prefetch" type="text/css" />
		<title>EM 2016</title>
	</head>

	<body id="login_body">
		<header>
		</header>
		<section id="content" class="center">
		</section>
<div class="wm-form">
	<h1>Login</h1>
	<form action="modules/user_login.php" method="post">
		<input type="text" name="username" placeholder="Username" required />
		<input type="password" name="password" placeholder="Password" required />
		
		<input type="submit" value="log in" />
		<div style="height:40px;"></div>
<?php
		//<div>
		//	<a id="register" href="#" >Hier erst einmal anmelden</a>
		// </div>
?>
	</form>
</div>
<footer>
      	Bitte Cookies zulassen!
</footer>
	</body>
</html>