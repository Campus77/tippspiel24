<div>
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
	$resultsOutput = "";
	$bonus = $db->getAllBonusQuestions();
	foreach ($bonus as $b) {
		// set static content in scroller
		$resultsOutput .= "<div class=\"scrollitem\">
			<div id=\"question\" class=\"desc\" style=\"margin-top:20px; min-width: 100% !important; max-width: 100% !important;\">{$b['question']}</div>
			<div id=\"result\" class=\"desc\" style=\"min-width: 100% !important; max-width: 100% !important;\">{$b['result']}</div>
		 </div>";

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
	
	echo "<div class=\"result\" id=\"outercontainer\">
        	    <div class=\"arrow arrow_left\"></div>
        	    <div class=\"arrow arrow_right\"></div>
                <div id=\"innercontainer\">
                    $resultsOutput
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
</table>";

?>
</div>
