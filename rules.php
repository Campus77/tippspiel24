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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="author" content="Marcel Daneyko" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link href="css/competition.css" rel="stylesheet" type="text/css" />
		<link href='fonts/roboto.css' rel="stylesheet" type="text/css" />
		<script src="js/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="js/hammer.min.js"></script>
		<title><?php echo Navi::getTitle(); ?></title>
	</head>

	<body>
		<header>
			<?php echo Navi::getMenu(); ?>
		</header>
		<section id="content" class="center" style="max-width: 500px;">
			<div class="rules">
			<h2>Spielregeln</h2>
			<br/>
			<h3>Tippmodus</h3>
			<br/>
			<ul>
			<li>Es wird das <b>genaue Ergebnis</b> getippt.</li>
			<li>Es wird das Ergebnis "zum Ende der Zweiten Halbzeit" getippt.</li>
			</ul>
			<br/>
			<h3>Bonustipps</h3>
			<br/>
			Bonustipps können nur vor Beginn des ersten Spiels abgegeben werden.
			<br/><br/>
			<h3>Punkteregel</h3>
			<br/>
			<table class="table rtable" id="user_ranking">
				<tbody>
					<tr>
						<td>richtiges Ergebnis</td>			
						<td>4</td>
					</tr>
					<tr>
						<td>richtige Tordifferenz</td>			
						<td>3</td>
					</tr>
					<tr>
						<td>richtige Tendenz</td>			
						<td>2</td>
					</tr>
				</tbody>
			</table>
			</div>
		</section>
	</body>
</html>