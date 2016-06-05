<div>
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

	include('functions.php');
	require_once('database.php');
	$db = Database::getInstance();


	$matchid = isset($_GET['match']) ? $_GET['match'] : FALSE;
	$match = $db->getMatch($matchid);
	$matchids = $db->getAllMatchIds();
	
	// no match played? show first match
	if (count($match) == 0) {
		$match = $db->getMatch($matchids[0]);
	}
	
	$matchid = $match['id'];
	$kickoff = $match['kickoff'];
	$location = $match['location'];
	$result1 = FormatValue($match['result1'], 2, FALSE);
	$result2 = FormatValue($match['result2'], 2, FALSE);
	$tsn1    = $match['tsn1'];
	$tfn1    = $match['tfn1'];
	$tflg1   = $match['tflg1'];
	$tsn2    = $match['tsn2'];
	$tfn2    = $match['tfn2'];
	$tflg2   = $match['tflg2'];
	
	for ($i = 0; $i < count($matchids); ++$i) {
		if ($matchids[$i] == $match['id']) {
			$prevIdx = ($i > 0 ? $matchids[$i - 1] : null);
			$nextIdx = ($i < count($matchids) - 1 ? $matchids[$i + 1] : null);
		}
	}
	
	$linkPrev = (is_null($prevIdx) ? "&nbsp;" : "<a href=\"results?match=$prevIdx\">&lt;&nbsp;vorheriges</a>");
	$linkNext = (is_null($nextIdx) ? "&nbsp;" : "<a href=\"results?match=$nextIdx\">n&auml;chstes&nbsp;&gt;</a>");
	
	echo "<div class=\"result\">
			  <div class=\"centercont\">
				 <div class=\"goal\">$result1</div>
				 <div class=\"flag flag-$tflg1\"></div>
				 <div class=\"desc\">$tsn1</div>
				 <div class=\"desc\">$tsn2</div>
				 <div class=\"flag flag-$tflg2\"></div>
				 <div class=\"goal\">$result2</div>
			  </div>
			  <div class=\"info\">
				<h2>$tfn1 - $tfn2</h2>
				<h2>$kickoff, $location</h2>
				<br/>
			  </div>
			  <div class=\"centercont\">
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
	
	$bets = $db->getAllBetsForMatch($matchid);
	foreach ($bets as $bet) {
		$bet1 = FormatValue($bet['bet1'], 2, $match['hidden']);
		$bet2 = FormatValue($bet['bet2'], 2, $match['hidden']);
		$points = (is_null($bet['bet1']) || is_null($bet['bet2']) || is_null($match['result1']) || is_null($match['result2']) ?
					'--' : CalcScoreForMatch($bet['bet1'], $bet['bet2'], $match['result1'], $match['result2']));

		$colors = Array( 4 => 'gold', 3 => 'silver', 2 => 'bronze');
		$color = (array_key_exists($points, $colors) ? $colors[$points] : 'standard');

		echo "<tr class=\"ranking_$color\"><td>{$bet['username']}</td><td>$bet1:$bet2</td><td>$points</td></tr>";
	}
			
	echo "</tbody>
	<tfoot></tfoot>
</table>";
?>
</div>
