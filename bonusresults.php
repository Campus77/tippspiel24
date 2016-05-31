<!--
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
 -->
 <?php
	include ('modules/check_session.php');
	include ('navi.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="author" content="Marcel Daneyko" />
		 <meta http-equiv="content-type" content="text/html; charset=iso-8859-15" />
		<link href="css/wm2014.css" rel="stylesheet" type="text/css" />
		<link href='http://fonts.googleapis.com/css?family=Fjalla+One' rel="stylesheet" type="text/css" />
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1" />
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<title><?php echo Navi::getTitle(); ?></title>
	</head>
	<body>
      
		<header>
			<?php echo Navi::getMenu(); ?>
		</header>
		<section id="content" class="center">
			<?php include('bonuslist.php'); ?>
		</section>
	</body>
</html>

