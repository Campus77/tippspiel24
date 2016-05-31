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
	<script>
		$(document).ready(function(){
			var idx = 0;
			var elTable = $('#user_ranking > tbody');
			var elQuestion = $('#question');
			var elResult = $('#result');
			
			$('#btnNext').click (function() {
				NextQuestion();
			});
			
			$('#btnPrev').click (function() {
				PrevQuestion();
			});
			
			function Format(val) {
				return (val == null ? '---' : val);
			}
			
			function FormatFlag(bet) {
				if (bet == null) return '---';
				subBets = bet.split(';');
				ret = '';
				for (i in subBets) {
					if (subBets[i] == '') {
						ret += '<span class="info">---</span>';
					}
					else {
						ret += '<span class="info"><img class="flag" src="' + flags[subBets[i]] + '" title="' + subBets[i] + '"/></span>';
					}
				}
				return ret;
			}
			
			function SumSubPoints(points) {
				if (points == null) return 0;
				subPoints = points.split(';');
				sum = 0;
				for (i in subPoints) {
					sum += parseInt(subPoints[i]);
				}
				return sum;
			}
			
			function UpdateTable() {
				elTable.empty();
				elQuestion.html(bonusresults[idx].question);
				switch (bonusresults[idx].type) {
					case 'TEAM':
					case 'TEAM2':
					case 'TEAM4':
					case 'TEAM8GROUP':
						result = FormatFlag(bonusresults[idx].result);
						break;
					default:
						result = Format(bonusresults[idx].result);
				}
				elResult.html(result);
				for (i in bonusresults[idx].bets) {
					e = bonusresults[idx].bets[i];
					switch (bonusresults[idx].type) {
						case 'TEAM':
						case 'TEAM2':
						case 'TEAM4':
						case 'TEAM8GROUP':
							bet = FormatFlag(e.bet);
							points = Format(SumSubPoints(e.points));
							break;
						default:
							bet = Format(e.bet);
							points = Format(e.points);
					}
					elTable.append('<tr><td>' + e.user + '</td><td>' + bet + '</td><td>' + points + '</td>');
				}
			}
			
			function NextQuestion() {
				idx = (idx + 1) % bonusresults.length;
				UpdateTable();
			}
	
			function PrevQuestion() {
				idx = (idx + bonusresults.length - 1) % bonusresults.length;
				UpdateTable();			
			}

			UpdateTable();
		});
	</script>
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

	// get all flags
	$query = "SELECT ShortName, Flag FROM competition_teams ORDER BY ShortName";
	$res_flags = mysqli_query($mySql, $query);
	$flags = array();
	while ($rowFlags = mysqli_fetch_assoc($res_flags)) {
		$flags[$rowFlags['ShortName']] = $rowFlags['Flag'];
	}
	echo "<script> var flags = ".json_encode($flags)."; </script>";
	
	// get all bonus questions
	$query = "SELECT b.ID, b.QUESTION, b.TYPE, b.RESULT, b.BET_LIMIT, b.POINTS FROM competition_bonus b ORDER BY ID";
	$res_bonus = mysqli_query($mySql, $query);
	$bonus = array();
	while ($rowBonus = mysqli_fetch_assoc($res_bonus)) {
		$bid = $rowBonus['ID'];
		$bonus[$bid] = array(
						'question' => $rowBonus['QUESTION'],
						'result' => $rowBonus['RESULT'],
						'type' => $rowBonus['TYPE'],
						'bets' => array()
					);

		$queryPerBet = "SELECT u.UserName, bb.BONUS_BET FROM competition_users u
						LEFT JOIN competition_bonus_bets bb
						ON u.ID = bb.USER_ID AND bb.BONUS_ID = $bid
						ORDER BY LOWER(u.UserName) ASC";
		$res_bonusBet = mysqli_query($mySql, $queryPerBet);
		while ($rowBonusBet = mysqli_fetch_assoc($res_bonusBet)) {
			$bonus[$bid]['bets'][] = array(
				'user' => $rowBonusBet['UserName'],
				'bet' => $rowBonusBet['BONUS_BET'],
				'points' => CalcScoreForBonus($rowBonusBet['BONUS_BET'], $rowBonus['RESULT'], $rowBonus['TYPE'], $rowBonus['POINTS'])
			);
		}
	}
	echo "<script> var bonusresults = ".json_encode(array_values($bonus))."; </script>";

	$linkPrev = "<a class=\"bonusNav\" id=\"btnPrev\" href=\"#\">&lt;&nbsp;zur&uuml;ck</a>";
	$linkNext = "<a class=\"bonusNav\" id=\"btnNext\" href=\"#\">weiter&nbsp;&gt;</a>";
	
	echo "<br/><br/><div class=\"result\">
			  <div class=\"centercont\">
				 <div id=\"question\" class=\"desc\" style=\"min-width: 100% !important; max-width: 100% !important;\">&nbsp;</div>
				 <div id=\"result\" class=\"desc\" style=\"min-width: 100% !important; max-width: 100% !important;\">&nbsp;</div>
			  </div>
			  <div class=\"centercontent\">
				<div class=\"naviResults\" style=\"float: left\">$linkPrev</div>
				<div class=\"naviResults\" style=\"float: right\">$linkNext</div>
			  </div>
			</div><br/>";
	
	echo "<table id=\"user_ranking\" class=\"table\">
			<thead>
			<tr>
				<td style=\"width: 40%\">Spieler</td>
				<td style=\"width: 50%\">Tipp</td>
				<td style=\"width: 10%\">Punkte</td>
			</tr>
	</thead>
	<tbody>";
	
		// $BET1 = FormatValue($row['BET1'], 2, $game['HIDDEN']);
		// $BET2 = FormatValue($row['BET2'], 2, $game['HIDDEN']);
		// $POINTS = (is_null($row['BET1']) || is_null($row['BET2']) || is_null($game['RESULT1']) || is_null($game['RESULT2']) ?
					// '--' : CalcScoreForMatch($row['BET1'], $row['BET2'], $game['RESULT1'], $game['RESULT2']));

		// $colors = Array( 4 => 'gold', 3 => 'silver', 2 => 'bronze');
		// $color = (array_key_exists($POINTS, $colors) ? $colors[$POINTS] : 'standard');

//		echo "<tr class=\"ranking_$color\"><td>{$row['Username']}</td><td>$BET1:$BET2</td><td>$POINTS</td></tr>";
			
	echo "</tbody>
	<tfoot></tfoot>
</table>";

?>
</div>
