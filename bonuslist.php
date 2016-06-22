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
						ret += '<span class="info"><div class="flag flag-' + flags[subBets[i]] + '" title="' + subBets[i] + '"/></span>';
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
					case 'TEAMGROUP':
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
						case 'TEAMGROUP':
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
	require_once('database.php');
	
	$db = Database::getInstance();

	// get all flags
	$teams = $db->getAllTeams();
	$flags = array();
	foreach ($teams as $t)
	{
		$flags[$t['shortname']] = $t['flag'];
	}
	echo "<script> var flags = ".json_encode($flags)."; </script>";
	
	// get all bonus questions
	$bonus = $db->getAllBonusQuestions();
	foreach ($bonus as $b) {
		$bid = $b['id'];

		$bonusbets = $db->getBonusBetForBonus($bid);
		
		foreach ($bonusbets as $bonusbet)
		{
			$bonus[$bid]['bets'][] = array(
				'user' => $bonusbet['username'],
				'bet' => $bonusbet['bonus_bet'],
				'points' => CalcScoreForBonus($bonusbet['bonus_bet'], $b['result'], $b['type'], $b['points'])
			);
		}
	}
	echo "<script> var bonusresults = ".json_encode(array_values($bonus))."; </script>";

	$linkPrev = "<a class=\"bonusNav\" id=\"btnPrev\" href=\"#\">&lt;&nbsp;vorheriges</a>";
	$linkNext = "<a class=\"bonusNav\" id=\"btnNext\" href=\"#\">nächstes&nbsp;&gt;</a>";
	
	echo "<br/><br/><div class=\"result\">
			  <div class=\"centercont\">
				 <div id=\"question\" class=\"desc\" style=\"min-width: 100% !important; max-width: 100% !important;\">&nbsp;</div>
				 <div id=\"result\" class=\"desc\" style=\"min-width: 100% !important; max-width: 100% !important;\">&nbsp;</div>
			  </div>
			  <div class=\"centercont\">
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
	<tbody></tbody>
	<tfoot></tfoot>
</table>";

?>
</div>
