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

	function sortByScore($a, $b) {
		return $a['score'] < $b['score'];
	}
	
	$ini_array = parse_ini_file("config.ini", TRUE);
	$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	// 1. get all users and prepare ranking table
	$ranking = Array();
	$query = "	SELECT u.ID AS uid, u.Username AS name FROM competition_users AS u";
	$res = mysqli_query($mySql, $query);
	while ($row = mysqli_fetch_assoc($res)) {
		$ranking[$row['uid']] = Array(
			'uid' => $row['uid'],
			'name' => $row['name'],
			'score' => 0);
	}


	// 2. get all bets with existing results (!= null)
	$query = "	SELECT b.USER_ID AS uid, u.Username AS name, b.BET1 as b1, b.BET2 AS b2, r.RESULT1 AS r1, r.RESULT2 AS r2
				FROM `competition_bets` as b
				LEFT JOIN `competition_results` AS r
				ON b.RESULT_ID = r.ID
				LEFT JOIN `competition_users` AS u
				ON b.USER_ID = u.ID
				WHERE NOT ISNULL(r.RESULT1) AND NOT ISNULL(r.RESULT2)";

	$res = mysqli_query($mySql, $query);
	while ($bet = mysqli_fetch_assoc($res)) {
		$ranking[$bet['uid']]['score'] += CalcScoreForMatch($bet['b1'], $bet['b2'], $bet['r1'], $bet['r2']);
	}

	// 3. get all bonus bets with existing results
	$query = "	SELECT bb.USER_ID AS uid, u.Username AS name, bb.BONUS_BET, b.RESULT, b.TYPE, b.POINTS
				FROM `competition_bonus_bets` as bb
				LEFT JOIN `competition_bonus` AS b
				ON bb.BONUS_ID = b.ID
				LEFT JOIN `competition_users` AS u
				ON bb.USER_ID = u.ID
				WHERE NOT ISNULL(b.RESULT)";

	$res = mysqli_query($mySql, $query);
	while ($bonus = mysqli_fetch_assoc($res)) {
		$bonusScore = CalcScoreForBonus($bonus['BONUS_BET'], $bonus['RESULT'], $bonus['TYPE'], $bonus['POINTS']);
//		echo "<script> console.log('". $bonus['name'] . ": $bonusScore " . array_sum(explode(';', $bonusScore))."');</script>";
		$ranking[$bonus['uid']]['score'] += array_sum(explode(';', $bonusScore));
	}

	usort($ranking, "sortByScore");
?>

<table id="user_ranking" class="table">
	<thead>
		<tr >
			<td style="width: 20%">Platz</td>
			<td style="width: 60%">Name</td>
			<td style="width: 20%">Punkte</td>
		</tr>
	</thead>
	<tbody>
<?php
	$pos = 0;
	$lastScore = -1;
	foreach ($ranking as $r)
	{
		if ($r['score'] != $lastScore) $pos++;
		$colors = Array('', 'gold', 'silver', 'bronze');
		$color = ($pos <= 3) ? $colors[$pos] : 'standard';
		echo "	<tr class=\"ranking_$color\">
					<td>$pos</td>
					<td>{$r['name']}</td>
					<td>{$r['score']}</td>
				</tr>";
		
		$lastScore = $r['score'];
	}
?>
	</tbody>
	<tfoot></tfoot>
</table>
</div>
