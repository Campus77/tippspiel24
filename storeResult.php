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
</head>
<body>
<?php
	require_once ('database.php');
	
	$db = Database::getInstance();
	
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

	$matches = $db->getAllMatches();
	$keys = array_keys($matches[0]);

	echo "<form id=\"myform\" action=\"storeResult.php\" method=\"post\"><table><tr>";
	foreach ($keys as $it) {
		echo "<td>$it</td>";
	}
	echo "</tr>";

	$c = 1;
	foreach ($matches as $row)
	{
		echo "<tr>";
		echo "<input type=\"hidden\" name=\"matchid_$c\" value=\"{$row['id']}\"/>";
		foreach ($keys as $it) {
			if ($it == 'result1' || $it == 'result2') {
				echo "<td><input style=\"width:25px;\" type=\"text\" name=\"".($it == "result1" ? "r1" : "r2")."_$c\" value=\"{$row[$it]}\" data-default=\"{$row[$it]}\"/></td>";
			}
			else {
				echo "<td>{$row[$it]}</td>";
			}
		}
		echo "</tr>";
		$c++;
	}
	echo "</table><input type=\"submit\" value=\"Speichern\"></form>";
	
	$teams = $db->getAllTeams();
	echo "<table>";
	foreach ($teams as $row)
	{
		echo "<tr>";
		foreach ($row as $key => $value)
			echo "<td>$value</td>";
		echo "</tr>";
	}
	echo "</table>";
	
//	mysqli_query($mySql, "UPDATE competition_results SET TEAM2 = 32 WHERE ID = 57");

?>
</body>
</html>