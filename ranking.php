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
	include ('modules/check_session.php');
	include ('navi.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="author" content="Marcel Daneyko" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link href="css/competition.css" rel="stylesheet" type="text/css" />
		<link href='fonts/roboto.css' rel="stylesheet" type="text/css" />
		<script src="js/jquery-1.10.2.min.js"></script>
		<title><?php echo Navi::getTitle(); ?></title>
	</head>

	<body>
		<header>
			<?php echo Navi::getMenu(); ?>
		</header>
		<section id="content" class="center">
			<?php
				include ('standing.php');
			?>
		</section>
	</body>
</html>