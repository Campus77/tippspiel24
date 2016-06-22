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
<html>
<head>
	<script src="js/jquery-1.10.2.min.js"></script>
	<script>
		$(document).ready(function(){

			$('table.admin tr').each(function(){
				$(this).click(function() {
					var ch = $(this).children();
					var id = ch.eq(0).prop('value');
					//console.log(id);
				});
			});
		
			$('#myform').submit(function() {
				$('#myform input').filter(function(){
					return $(this).prop('name').match(/^matchid_\d+/);
				}).each(function(){
					// get id
					var entryID = $(this).prop('name').match(/^matchid_(\d+)/)[1];
					var r1 = $('#myform input:text[name=r1_' + entryID + ']');
					var r2 = $('#myform input:text[name=r2_' + entryID + ']');
					
					if ((r1.prop('value') == r1.data('default')) && (r2.prop('value') == r2.data('default'))) {
						$(this).prop('disabled', true);
						r1.prop('disabled', true);
						r2.prop('disabled', true);
					}
					//console.log($(this).prop('name') + ': ' + $(this).prop('value') + ' (' + $(this).data('default') + ') : ' + $(this).prop('disabled'));
				});
			});
		});
	</script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="css/flags.css" rel="stylesheet" type="text/css" />
	<link href="css/competition.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
	require_once ('database.php');
	
	$db = Database::getInstance();

	function showTeamCombo($name) {
		global $db;
		$teams = $db->getAllTeams();
		echo "<select name=\"$name\"><option value=\"---\">---</option>";
		foreach ($teams as $team)
		{
			echo "<option value=\"{$team['id']}\">{$team['fullname']}</option>";			
		}
		echo "<select>";
	}

	function showStadiumCombo() {
		global $db;
		$stadiums = $db->getAllStadiums();
		echo "<select name=\"location_id\"><option value=\"---\">---</option>";
		foreach ($stadiums as $stadium)
		{
			echo "<option value=\"{$stadium['id']}\">{$stadium['name']}</option>";			
		}
		echo "<select>";
	}

	
	if (isset($_POST['action']) && $_POST['action'] == 'updateresults')
	{
		foreach ($_POST as $key => $value)
		{
			if (preg_match("/^matchid_(\\d+)$/", $key, $matches)) {
				$idx = $matches[1];
				$result1 = $_POST['r1_'.$idx];
				$result2 = $_POST['r2_'.$idx];
				if ($result1 === "") $result1 = null;
				if ($result2 === "") $result2 = null;
				$matchid = $value;
				$db->updateMatchResult($matchid, $result1, $result2);
			}
		}
	}
	else if (isset($_POST['action']) && $_POST['action'] == 'addmatch') {
		$team1_id = $_POST['team1_id'] == '---' ? null : $_POST['team1_id'];
		$team2_id = $_POST['team2_id'] == '---' ? null : $_POST['team2_id'];
		$db->addMatch($_POST['kickoff'], $team1_id, $team2_id, $_POST['location_id']);
	}

	$matches = $db->getAllMatches();
	$keys = array('id', 'kickoff', 'location', 'team1', 'result1', 'result2', 'team2');

	echo "<form id=\"myform\" action=\"storeResult.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"updateresults\"><table class='admin'>";

	$c = 1;
	foreach ($matches as $row)
	{
		echo "<tr>";
		echo "<input type=\"hidden\" name=\"matchid_$c\" value=\"{$row['id']}\"/>";
		foreach ($keys as $it) {
			if ($it == 'result1' || $it == 'result2') {
				echo "<td><input style=\"width:25px;\" type=\"text\" name=\"".($it == "result1" ? "r1" : "r2")."_$c\" value=\"{$row[$it]}\" data-default=\"{$row[$it]}\"/></td>";
			}
			else if ($it == 'team1') {
				echo "<td><div class=\"flag flag-{$row['flag1']}\"></div><span class=\"team\">{$row[$it]}</span></td>";
			}
			else if ($it == 'team2') {
				echo "<td></div><span class=\"team\">{$row[$it]}</span><div class=\"flag flag-{$row['flag2']}\"></td>";
			}
			else {
				echo "<td>{$row[$it]}</td>";
			}
		}
		echo "</tr>";
		$c++;
	}
	echo "</table><input type=\"submit\" value=\"storeresults\"></form>";
	
	echo "<form action=\"storeResult.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"addmatch\"><table><tr>";
	echo "<input type=\"text\" name=\"kickoff\" value=\"". (isset($_POST['kickoff']) ? $_POST['kickoff'] : "2016-06-10 18:00:00") . "\">";
	showStadiumCombo();
	showTeamCombo('team1_id');
	showTeamCombo('team2_id');
	echo "<input type=\"submit\" value=\"neues Match\">";
	echo "</form>";
	
	
?>
</body>
</html>