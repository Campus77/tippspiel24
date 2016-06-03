<?php
			
function showCombo($bid, $subid, $teams, $selected, $inGroup) {
	$name = "b$bid"."_$subid";
	$q = "";

	// show group letter
	if ($inGroup) {
		$groupLetter = chr($subid + 64);
		$q .= "<label for=\"$name\">$groupLetter&nbsp;</label>";
	}

	$q .= "<select name=\"$name\">";
	$q .= "<option value=\"---\">---</option>";
	$selected = is_null($selected) ? "---" : $selected;
	foreach ($teams as $t) {
		if ($inGroup && $groupLetter != $t['ingroup']) continue;
		$s = ($selected == $t['shortname'] ? ' selected="selected"' : '');
		$q .= "<option value=\"{$t['shortname']}\"$s>{$t['fullname']}</option>";
	}
	$q .= "</select><br/>";

	return $q;
}

function showCombos($bid, $max, $teams, $selectedCombined, $inGroup) {
	$r = "";
	if (is_null($selectedCombined)) {
		$selectedCombined = str_repeat(";", $max - 1);
	}
	$selectedArray = explode(';', $selectedCombined);
	for ($i = 1; $i <= $max; ++$i) {
		$r .= showCombo($bid, $i, $teams, $selectedArray[$i - 1], $inGroup);
	}
	return $r;
}

// ************* Bonusfragen *************

// teams
$teams = $db->getAllTeams();

// questions
$userid = $_SESSION['activeUserId'];
$bonusbets = $db->getBonusBetsForUser($userid);
$allowed = $db->areBonusBetsAllowed();
$numGroups = $db->getNumGroups();

echo "<div class='bonus'><form class=\"wm-form\" name=\"bonusForm\" action=\"submitBonus.php\" method=\"post\">";

$showSubmit = $allowed;
foreach ($bonusbets as $row)
{
	$id = $row['id'];

	echo "<div class='match'>";
	echo $row['question']."<br/>(".$row['points']." Punkte)<br/><br/>";
	switch ($row['type']) {
		case 'TEAM':
			echo showCombos($id, 1, $teams, $row['bonus_bet'], false);
			break;
		case 'TEAM2':
			echo showCombos($id, 2, $teams, $row['bonus_bet'], false);
			break;
		case 'TEAM4':
			echo showCombos($id, 4, $teams, $row['bonus_bet'], false);
			break;
		case 'TEAMGROUP':
			echo showCombos($id, $numGroups, $teams, $row['bonus_bet'], true);
			break;
		case 'INT':
			echo "<input type=\"text\" name=\"b".$id."_1\" value=\"".$row['bonus_bet']."\">";
			echo "<br/>";
			break;
		default:
			break;
	}
	echo "</div><br/><br/>";
}
if ($showSubmit) {
	echo "<div style=\"width: 100%; height:40px\"><input type=\"submit\" value=\"Speichern\"></div>";
}
echo "</form></div>";

?>