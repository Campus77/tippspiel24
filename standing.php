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
	require_once('database.php');
	
	$db = Database::getInstance();

	function transcodeString($a) {
		return iconv("UTF-8", "ASCII//TRANSLIT", strtolower($a));
	}

	function sortByScore($a, $b) {
		if ($a['score'] == $b['score']) {
			// sort by name
			return transcodeString($a['name']) > transcodeString($b['name']);
		}
		return $a['score'] < $b['score'];
	}
	
	// 1. get all users and prepare ranking table
	$ranking = Array();
	$users = $db->getUsers();
	foreach ($users as $user)
	{
		$ranking[$user['uid']] = Array(
			'uid' => $user['uid'],
			'name' => $user['name'],
			'score' => 0,
			'bonus' => 0);
	}

	// 2. get all bets with existing results
	$bets = $db->getAllBetsForFinishedMatches();

	foreach ($bets as $bet)
	{
		$ranking[$bet['uid']]['score'] += CalcScoreForMatch($bet['b1'], $bet['b2'], $bet['r1'], $bet['r2']);
	}

	// 3. get all bonus bets with existing results
	$bonusBets = $db->getAllFinishedBonusBets();
	foreach ($bonusBets as $bonus) {
		$bonusScore = CalcScoreForBonus($bonus['bonus_bet'], $bonus['result'], $bonus['type'], $bonus['points']);
//		echo "<script> console.log('". $bonus['name'] . ": $bonusScore " . array_sum(explode(';', $bonusScore))."');</script>";
		$ranking[$bonus['uid']]['score'] += array_sum(explode(';', $bonusScore));
		$ranking[$bonus['uid']]['bonus'] += array_sum(explode(';', $bonusScore));
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
	$sameRankCount = 0;
	$pos = 0;

	$lastScore = -1;
	foreach ($ranking as $r)
	{
		$self = ($r['uid'] == $_SESSION['activeUserId']) ? " self" : "";
		if ($r['score'] != $lastScore)
		{
			$pos = $sameRankCount + ++$pos;
			$sameRankCount = 0;
		}
		else
		{
			$sameRankCount++;
		}
		$colors = Array('', 'gold', 'silver', 'bronze');
		$color = ($pos <= 3) ? $colors[$pos] : 'standard';
		echo "	<tr class=\"ranking_$color$self\">
					<td>$pos</td>
					<td>{$r['name']}</td>
					<td>{$r['score']}<span class=\"tiny-bonus\">({$r['bonus']})</span></td>
				</tr>\n";

		$lastScore = $r['score'];
	}
?>
	</tbody>
	<tfoot></tfoot>
</table>
</div>
