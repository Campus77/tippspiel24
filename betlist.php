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
	
	// fetch all matches
	$matchids = $db->getAllMatchIds();
	$bet_data = array();
	
	// generate all match result boxes
	echo "<div id=\"outercontainer\" class=\"result\">";
	echo "<div class=\"arrow arrow_left\"></div>";
	echo "<div class=\"arrow arrow_right\"></div>";
	echo "<div id=\"innercontainer\">";
	foreach ($matchids as $id)
	{
		$match = $db->getMatch($id);
		$matchid = $match['id'];
		$kickoffDate = new DateTime($match['kickoff']);
		$kickoff = $kickoffDate->format('d.m.Y H:i');
		$location = $match['location'];
		$result1 = FormatValue($match['result1'], 2, FALSE);
		$result2 = FormatValue($match['result2'], 2, FALSE);
		$tsn1    = FormatValue($match['tsn1'], 3, FALSE);
		$tfn1    = FormatValue($match['tfn1'], 3, FALSE);
		$tflg1   = $match['tflg1'];
		$tsn2    = FormatValue($match['tsn2'], 3, FALSE);
		$tfn2    = FormatValue($match['tfn2'], 3, FALSE);
		$tflg2   = $match['tflg2'];
		echo "<div class=\"scrollitem\">
				  <div class=\"centercont\">
					 <div class=\"goal\">$result1</div>
					 <div class=\"flag flag-$tflg1\"></div>
					 <div class=\"desc\">$tsn1</div>
					 <div class=\"vs\">:</div>
					 <div class=\"desc\">$tsn2</div>				 
					 <div class=\"flag flag-$tflg2\"></div>
					 <div class=\"goal\">$result2</div>
				  </div>
				  <div class=\"info\">
					<h2>$tfn1 - $tfn2</h2>
					<h2><strong>$kickoff</strong></h2>
					<h2>$location</h2>
				  </div>
				</div>";
		// store bet data for this match
		$bets = $db->getAllBetsForMatch($id);
		$bet_per_match_data = array();
		foreach ($bets as $bet) {
			$bet1 = FormatValue($bet['bet1'], 2, $match['hidden']);
			$bet2 = FormatValue($bet['bet2'], 2, $match['hidden']);
			$points = (is_null($bet['bet1']) || is_null($bet['bet2']) || is_null($match['result1']) || is_null($match['result2']) ?
						'--' : CalcScoreForMatch($bet['bet1'], $bet['bet2'], $match['result1'], $match['result2']));

			$self = ($bet['id'] == $_SESSION['activeUserId']) ? " self" : "";
			$colors = Array( 4 => 'gold', 3 => 'silver', 2 => 'bronze');
			$color = (array_key_exists($points, $colors) ? $colors[$points] : 'standard');

			$bet_per_match_data[] = array($color, $bet['id'] == $_SESSION['activeUserId'], $bet['username'], $bet1, $bet2, $points);
		}
		$bet_data[] = $bet_per_match_data;

	}
	echo "</div></div>";

	echo "<script type=\"text/javascript\">var bet_data = " . json_encode($bet_data) . ";</script>";
	echo "<br clear=\"all\"/>";
	
	$initialMatchId = $db->getRecentMatchId();
	$initialMatchPos = array_search($initialMatchId, $matchids);

	echo "<table id=\"user_ranking\" class=\"table\">
			<thead>
			<tr>
				<td style=\"width: 70%\">Spieler</td>
				<td style=\"width: 20%\">Tipp</td>
				<td style=\"width: 10%\">Punkte</td>
			</tr>
	</thead>
	<tbody>";
	
	echo "</tbody></table>";
	echo "<script type=\"text/javascript\">var initPos = $initialMatchPos;</script>";
?>
</div>
