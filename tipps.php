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
	include ('functions.php');
?>
<!DOCTYPE html>
<html land="de">
	<head>
		<meta name="author" content="Marcel Daneyko" />
		<meta charset="iso-8859-15" />
		<link href="css/competition.css" rel="stylesheet" type="text/css" />
		<link href="css/flags.css" rel="stylesheet" type="text/css" />
		<link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css' />
		<!--link href='https://fonts.googleapis.com/css?family=Dosis:300' rel='stylesheet' type='text/css' /-->
		<link href='fonts/roboto.css' rel="stylesheet" type="text/css" />
		<meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1" />
		
		<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script src="scripts.js"></script>
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
		//get all users from database
		//calculate ranking
		$ini_array = parse_ini_file("config.ini", TRUE);
		$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		// send mysql server time as reference timestamp --> TODO: auslagern in functions.php
		$ts = mysqli_fetch_row(mysqli_query($mySql, "SELECT DATE_FORMAT(NOW(), \"%Y-%m-%dT%H:%i:%s\")"))[0];
		echo "<script>
				var timediff = new Date('$ts') - new Date();
			</script>";
		
		$uid = $_SESSION['activeUserId'];
		
		$sql = "SELECT p.RESULT, DATE_FORMAT(p.Anpfiff, '%d.%m.%Y %H:%i') AS Anpfiff, (NOW() < p.Anpfiff) AS tippable, p.Ort,
				t1.ShortName AS TSN1, t1.FullName AS TFN1, t1.Flag AS TFLG1, t2.ShortName AS TSN2, t2.FullName AS TFN2, t2.Flag AS TFLG2, b.BET1, b.BET2,
				r.RESULT1, r.RESULT2
				FROM competition_plan p
				LEFT JOIN competition_results r
				ON r.ID = p.RESULT
				LEFT JOIN competition_teams t1
				ON r.TEAM1 = t1.ID
				LEFT JOIN competition_teams t2
				ON r.TEAM2 = t2.ID
				LEFT JOIN competition_bets b
				ON p.RESULT = b.RESULT_ID AND b.USER_ID = $uid
				ORDER BY p.Anpfiff, p.RESULT ASC;";
				
		$result = mysqli_query($mySql, $sql);
		if (!$result) {
		  die('Error: ' . mysqli_error($mySql));
		}
		// Fetch each row
		while ($row = mysqli_fetch_assoc($result))
		{
			$Anpfiff  = $row['Anpfiff'];
			$tippable = $row['tippable'];

			$BET1  = FormatValue($row['BET1'], 2, false);
			$BET2  = FormatValue($row['BET2'], 2, false);

			$TSN1  = is_null($row['TSN1']) ? '---' : $row['TSN1'];
			$TFN1  = is_null($row['TFN1']) ? '---' : $row['TFN1'];
			$TFLG1 = is_null($row['TFLG1']) ? 'img/flags/unknown.png' : $row['TFLG1'];

			$TSN2  = is_null($row['TSN2']) ? '---' : $row['TSN2'];
			$TFN2  = is_null($row['TFN2']) ? '---' : $row['TFN2'];
			$TFLG2 = is_null($row['TFLG2']) ? 'img/flags/unknown.png' : $row['TFLG2'];

			$Ort   = $row['Ort'];
			$gID   = $row['RESULT'];

			$gameClass = ($tippable ? 'game_open' : 'game_closed');

			echo "<div class=\"game $gameClass\" id=\"game\" value=\"$gID\">
				  <div class=\"centercont\">
					 <div class=\"goal\" value=\"$BET1\">$BET1</div>
					 <div class=\"flag\"><img id=\"goal\" src=\"$TFLG1\" alt=\"$TSN1\"></div>
					 <div class=\"desc\">$TSN1</div>
					 <div class=\"desc\">$TSN2</div>
					 <div class=\"flag\"><img id=\"goal\" src=\"$TFLG2\" alt=\"$TSN2\"></div>
					 <div class=\"goal\" value=\"$BET2\">$BET2</div>
				  </div>
				  <div class=\"info\">
						<h2>$TFN1 ".(is_null($row['RESULT1']) || is_null($row['RESULT2']) ? "-" : "{$row['RESULT1']} : {$row['RESULT2']}")." $TFN2</h2>
						<span id=\"kickoff\">$Anpfiff, $Ort</span>
						<h2 id=\"timetobet\">&nbsp;</h2>
						<br/>
				  </div>
				   </div><br />";
		}
		// Free result set
		mysqli_free_result($result);
		  
		$mySql->close();
		?>
		</section>
	</body>
</html>

