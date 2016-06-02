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
		<meta name="author" content="Marcel Daneyko">
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-15" />
		<link href="css/competition.css" rel="stylesheet" type="text/css">
		<link href='http://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Fjalla+One' rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
      <!--script src="scripts.js"></script-->
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
			
		function showCombo($bid, $subid, $teams, $selected, $inGroup, $expired) {
			$name = "b$bid"."_$subid";
			$q = "";

			// show group letter
			if ($inGroup) {
				$groupLetter = chr($subid + 64);
				$q .= "<label for=\"$name\">$groupLetter&nbsp;</label>";
			}

			if ($expired == 1) {
				$team = "---";
				foreach ($teams as $t) {
					if ($selected == $t['ShortName']) {
						$team = $t['FullName'];
					}
				}
				$q .= "<h2>$team</h2><br/>";
			}
			else {
				$q .= "<select name=\"$name\">";
				$q .= "<option value=\"---\">---</option>";
				$selected = is_null($selected) ? "---" : $selected;
				foreach ($teams as $t) {
					if ($inGroup && $groupLetter != $t['InGroup']) continue;
					$s = ($selected == $t['ShortName'] ? ' selected="selected"' : '');
					$q .= "<option value=\"{$t['ShortName']}\"$s>{$t['FullName']}</option>";
				}
				$q .= "</select><br/>";
			}
			return $q;
		}

		function showCombos($bid, $max, $teams, $selectedCombined, $inGroup, $expired) {
			$r = "";
			if (is_null($selectedCombined)) {
				$selectedCombined = str_repeat(";", $max - 1);
			}
			$selectedArray = explode(';', $selectedCombined);
			for ($i = 1; $i <= $max; ++$i) {
				$r .= showCombo($bid, $i, $teams, $selectedArray[$i - 1], $inGroup, $expired);
			}
			return $r;
		}
		
		$ini_array = parse_ini_file("config.ini", TRUE);
		$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$aId = $_SESSION['activeUserId'];

		// ************* Bonusfragen *************
		
		// teams
		$sql = "SELECT ID, ShortName, FullName, Flag, InGroup FROM competition_teams ORDER BY FullName ASC;";
		$result = mysqli_query($mySql, $sql);
		if (!$result) {
		  die('Error: ' . mysqli_error($mySql));
		}
		$teams = Array();
		while ($row = mysqli_fetch_assoc($result))
		{
			$teams[] = $row;
		}
		// questions
		$sql = "SELECT ID, QUESTION, TYPE, POINTS, (BET_LIMIT < NOW()) AS EXPIRED, BONUS_BET
				FROM competition_bonus b
				LEFT JOIN competition_bonus_bets bb
				ON b.ID = bb.BONUS_ID
				AND bb.USER_ID = $aId
				ORDER BY b.ID ASC;";

		$result = mysqli_query($mySql, $sql);
		if (!$result) {
		  die('Error: ' . mysqli_error($mySql));
		}
		echo "<div class='bonus'><form class=\"wm-form\" name=\"bonusForm\" action=\"submitBonus.php\" method=\"post\">";
		// Fetch each row
		$showSubmit = false;
		while ($row = mysqli_fetch_assoc($result))
		{
			$id = $row['ID'];
			$expired = $row['EXPIRED'];

			$showSubmit |= !$expired;

			echo "<div class='game'>";
			echo $row['QUESTION']."<br/>(".$row['POINTS']." Punkte)<br/><br/>";
			switch ($row['TYPE']) {
				case 'TEAM':
					echo showCombos($id, 1, $teams, $row['BONUS_BET'], false, $expired);
					break;
				case 'TEAM2':
					echo showCombos($id, 2, $teams, $row['BONUS_BET'], false, $expired);
					break;
				case 'TEAM4':
					echo showCombos($id, 4, $teams, $row['BONUS_BET'], false, $expired);
					break;
				case 'TEAM8GROUP':
					echo showCombos($id, 8, $teams, $row['BONUS_BET'], true, $expired);
					break;
				case 'INT':
					if ($expired) {
						echo "<h2>".(is_null($row['BONUS_BET']) ? "---" : $row['BONUS_BET'])."</h2>";
					} else {
						echo "<input type=\"text\" name=\"b".$id."_1\" value=\"".$row['BONUS_BET']."\">";
					}
					
					echo "<br/>";
					break;
				default:
					break;
			}
			echo "</div><br/><br/>";
		}
		if ($showSubmit) {
			echo "<input type=\"submit\" value=\"Speichern\">";
		}
		echo "</form></div>";
				
		// Free result set
		mysqli_free_result($result);
		  
		$mySql->close();
		?>
		</section>
	</body>
</html>

