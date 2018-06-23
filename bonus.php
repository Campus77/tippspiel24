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
	include_once ('modules/check_session.php');
	include ('navi.php');
	require_once ('database.php');
	
	$db = Database::getInstance();
	
	if ($db->areBonusBetsAllowed()) {
		$php_script = 'bonusbet.php';
		$js_script = "";
	}
	else {
		$php_script = 'bonuslist.php';
		$listscripts = array("hammer.min", "jquery.hammer", "bonuslist", "swipe");
		$js_script = "";
		foreach ($listscripts as $s) {
			$js_script .= "<script src=\"js/$s.js\"></script>\n";
		}
		$js_script .= "<script>var initPos = 0;</script>";
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="author" content="Marcel Daneyko" />
		<meta charset="utf-8" />
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link href="css/competition.css" rel="stylesheet prefetch" type="text/css" />
		<link href="css/flags.css" rel="stylesheet" type="text/css" />
		<link href='fonts/roboto.css' rel="stylesheet prefetch" type="text/css" />
		<script src="js/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<?php echo $js_script; ?>
		<title><?php echo Navi::getTitle(); ?></title>
	</head>
	<body>
		<header>
			<?php
				echo Navi::getMenu();
			?>
		</header>
		<section id="content" class="center">
			<?php include ($php_script); ?>
		</section>
	</body>
</html>

