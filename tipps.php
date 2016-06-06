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
	require_once ('database.php');
	include ('functions.php');
	
	$db = Database::getInstance();
	$ts = $db->getServerTime();
	$uid = $_SESSION['activeUserId'];
	$bets = $db->getBetsForUser($uid);

?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="author" content="Marcel Daneyko" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link href="css/competition.css" rel="stylesheet" type="text/css" />
		<link href="css/flags.css" rel="stylesheet" type="text/css" />
		<link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css' />
		<link href='fonts/roboto.css' rel="stylesheet" type="text/css" />
		<script src="js/jquery-1.10.2.min.js"></script>
		<script src="js/scripts.js"></script>
		<title><?php echo Navi::getTitle(); ?></title>
	</head>
	<body>
		<header>
			<?php
				echo Navi::getMenu();
			?>
		</header>
		<section id="content" class="center">
			<?php

		// send mysql server time as reference timestamp
		echo "<script>
				var timediff = new Date('$ts') - new Date();
			</script>";
		
		foreach ($bets as $row)
		{
			$id = $row['id'];
			$kickoff = $row['kickoff'];
			$location = $row['location'];

			$bet1  = FormatValue($row['bet1'], 2, false);
			$bet2  = FormatValue($row['bet2'], 2, false);

			$tsn1  = is_null($row['tsn1']) ? '---' : $row['tsn1'];
			$tfn1  = is_null($row['tfn1']) ? '---' : $row['tfn1'];
			$tflg1 = is_null($row['tflg1']) ? 'img/flags/unknown.png' : $row['tflg1'];

			$tsn2  = is_null($row['tsn2']) ? '---' : $row['tsn2'];
			$tfn2  = is_null($row['tfn2']) ? '---' : $row['tfn2'];
			$tflg2 = is_null($row['tflg2']) ? 'img/flags/unknown.png' : $row['tflg2'];

			$matchClass = ($row['open'] ? 'match_open' : 'match_closed');

			echo "<div class=\"match $matchClass\" id=\"match\" value=\"$id\">
				  <div class=\"centercont\">
					 <div class=\"goal\" value=\"$bet1\">$bet1</div>
					 <div class=\"flag flag-$tflg1\"></div>
					 <div class=\"desc\">$tsn1</div>
					 <div class=\"desc\">$tsn2</div>
					 <div class=\"flag flag-$tflg2\"></div>
					 <div class=\"goal\" value=\"$bet2\">$bet2</div>
				  </div>
				  <div class=\"info\">
						<h2>$tfn1 ".(is_null($row['result1']) || is_null($row['result2']) ? "-" : "<b>{$row['result1']} : {$row['result2']}</b>")." $tfn2</h2>
						<b><span id=\"kickoff\">$kickoff</span></b>
						<h2>$location</h2>
						<br/>
						<h2 id=\"timetobet\">&nbsp;</h2>
						<br/>
				  </div>
				   </div><br />";
		}

		?>
		</section>
	</body>
</html>

