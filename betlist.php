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
 <div>
<?php
	include('functions.php');
	// TODO: SQL kram auslagern!

	$ini_array = parse_ini_file("config.ini", TRUE);
	$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	// 1a. check if Game ID is set
	if (isset($_GET['game'])) {
		$query = "	SELECT p.Anpfiff > NOW() AS HIDDEN, p.RESULT, DATE_FORMAT(p.Anpfiff, '%d.%m.%Y %H:%i') AS KICKOFF,  p.Ort,
					t1.ShortName AS TSN1, t1.FullName AS TFN1, t1.Flag AS TFLG1,
					t2.ShortName AS TSN2, t2.FullName AS TFN2, t2.Flag AS TFLG2,
					r.RESULT1, r.RESULT2
					FROM wm2014_plan p
					LEFT JOIN wm2014_results r
					ON r.ID = p.RESULT
					LEFT JOIN wm2014_teams t1
					ON t1.ID = r.TEAM1
					LEFT JOIN wm2014_teams t2
					ON t2.ID = r.TEAM2
					WHERE RESULT = ".$_GET['game'];
	}
	// 1b. if no game id given -> get game id of nearest match
	else {
		$query = "	SELECT p.Anpfiff > NOW() AS HIDDEN, p.RESULT, DATE_FORMAT(p.Anpfiff, '%d.%m.%Y %H:%i') AS KICKOFF, p.Ort,
					t1.ShortName AS TSN1, t1.FullName AS TFN1, t1.Flag AS TFLG1,
					t2.ShortName AS TSN2, t2.FullName AS TFN2, t2.Flag AS TFLG2,
					r.RESULT1, r.RESULT2
					FROM wm2014_plan p
					LEFT JOIN wm2014_results r
					ON r.ID = p.RESULT
					LEFT JOIN wm2014_teams t1
					ON t1.ID = r.TEAM1
					LEFT JOIN wm2014_teams t2
					ON t2.ID = r.TEAM2
					WHERE NOW() > p.Anpfiff
					ORDER BY TIMEDIFF(NOW(), p.Anpfiff) ASC, p.RESULT ASC
					LIMIT 0,1";
	}
				
	$game = mysqli_fetch_assoc(mysqli_query($mySql, $query));
	
	$RESULT1 = FormatValue($game['RESULT1'], 2, FALSE);
	$RESULT2 = FormatValue($game['RESULT2'], 2, FALSE);
	$TSN1    = $game['TSN1'];
	$TFN1    = $game['TFN1'];
	$TFLG1   = $game['TFLG1'];
	$TSN2    = $game['TSN2'];
	$TFN2    = $game['TFN2'];
	$TFLG2   = $game['TFLG2'];
	
	$res_matchIds = mysqli_query($mySql, "SELECT RESULT FROM wm2014_plan ORDER BY Anpfiff ASC;");
	while ($rowMatchId = mysqli_fetch_assoc($res_matchIds)) {
		$matchIds[] = $rowMatchId['RESULT'];
	}
	
	for ($i = 0; $i < count($matchIds); ++$i) {
		if ($matchIds[$i] == $game['RESULT']) {
			$prevIdx = ($i > 0 ? $matchIds[$i - 1] : null);
			$nextIdx = ($i < count($matchIds) - 1 ? $matchIds[$i + 1] : null);
		}
	}
	
	$linkPrev = (is_null($prevIdx) ? "&nbsp;" : "<a href=\"results.php?game=$prevIdx\">&lt;&nbsp;zur&uuml;ck</a>");
	$linkNext = (is_null($nextIdx) ? "&nbsp;" : "<a href=\"results.php?game=$nextIdx\">weiter&nbsp;&gt;</a>");
	
	echo "<div class=\"result\">
			  <div class=\"centercont\">
				 <div class=\"goal\">$RESULT1</div>
				 <div class=\"flag\"><img src=\"$TFLG1\" alt=\"$TSN1\"></div>
				 <div class=\"desc\">$TSN1</div>
				 <div class=\"desc\">$TSN2</div>
				 <div class=\"flag\"><img src=\"$TFLG2\" alt=\"$TSN2\"></div>
				 <div class=\"goal\">$RESULT2</div>
			  </div>
			  <div class=\"info\">
				<h2>$TFN1 - $TFN2</h2>
				<h2>{$game['KICKOFF']}, {$game['Ort']}</h2>
				<br/>
			  </div>
			  <div class=\"centercontent\">
				<div class=\"naviResults\" style=\"float: left\">$linkPrev</div>
				<div class=\"naviResults\" style=\"float: right\">$linkNext</div>
			  </div>
			</div><br/>";
	
	echo "<table id=\"user_ranking\" class=\"table\">
			<thead>
			<tr>
				<td style=\"width: 70%\">Spieler</td>
				<td style=\"width: 20%\">Tipp</td>
				<td style=\"width: 10%\">Punkte</td>
			</tr>
	</thead>
	<tbody>";
	
	$query = " SELECT u.ID, Username, BET1, BET2 FROM wm2014_users u
				LEFT JOIN wm2014_bets b
				ON b.RESULT_ID = ".$game['RESULT']." AND u.ID=b.USER_ID
				ORDER BY LOWER(u.Username) ASC";
	$res = mysqli_query($mySql, $query);
	while ($row = mysqli_fetch_assoc($res)) {
		$BET1 = FormatValue($row['BET1'], 2, $game['HIDDEN']);
		$BET2 = FormatValue($row['BET2'], 2, $game['HIDDEN']);
		$POINTS = (is_null($row['BET1']) || is_null($row['BET2']) || is_null($game['RESULT1']) || is_null($game['RESULT2']) ?
					'--' : CalcScoreForMatch($row['BET1'], $row['BET2'], $game['RESULT1'], $game['RESULT2']));

		$colors = Array( 4 => 'gold', 3 => 'silver', 2 => 'bronze');
		$color = (array_key_exists($POINTS, $colors) ? $colors[$POINTS] : 'standard');

		echo "<tr class=\"ranking_$color\"><td>{$row['Username']}</td><td>$BET1:$BET2</td><td>$POINTS</td></tr>";
	}
			
	echo "</tbody>
	<tfoot></tfoot>
</table>";
?>
</div>
