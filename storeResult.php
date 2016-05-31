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
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script>
		$(document).ready(function(){

			$('#myform').submit(function() {
				$('#myform input').filter(function(){
					return $(this).prop('name').match(/^gid_\d+/);
				}).each(function(){
					// get id
					var entryID = $(this).prop('name').match(/^gid_(\d+)/)[1];
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
	$ini_array = parse_ini_file("config.ini", TRUE);
	$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	foreach ($_POST as $key => $value)
	{
		if (preg_match("/^gid_(\\d+)$/", $key, $matches)) {
			$idx = $matches[1];
			$r1 = $_POST['r1_'.$idx];
			$r2 = $_POST['r2_'.$idx];
			$gid = $value;
			$sql = "UPDATE competition_results SET RESULT1=$r1, RESULT2=$r2 WHERE ID=$gid";
			mysqli_query($mySql, $sql);
		}
	}

	$sql = "SELECT p.RESULT, p.Anpfiff AS KICKOFF, p.Ort AS ORT,
			t1.FullName AS TEAM1, t2.FullName AS TEAM2,
			r.RESULT1, r.RESULT2
			FROM competition_plan p
			LEFT JOIN competition_results r
			ON r.ID = p.RESULT
			LEFT JOIN competition_teams t1
			ON r.TEAM1 = t1.ID
			LEFT JOIN competition_teams t2
			ON r.TEAM2 = t2.ID
			/*WHERE p.Anpfiff < NOW()*/
			ORDER BY p.Anpfiff, p.RESULT ASC;";

	$result = mysqli_query($mySql, $sql);
	$items = Array("RESULT", "KICKOFF", "ORT", "TEAM1", "TEAM2", "RESULT1", "RESULT2");

	echo "<form id=\"myform\" action=\"storeResult.php\" method=\"post\"><table><tr>";
	foreach ($items as $it) {
		echo "<td>$it</td>";
	}
	echo "</tr>";

	$c = 1;
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<tr>";
		echo "<input type=\"hidden\" name=\"gid_$c\" value=\"{$row['RESULT']}\"/>";
		foreach ($items as $it) {
			if ($it == 'RESULT1' || $it == 'RESULT2') {
				echo "<td><input style=\"width:25px;\" type=\"text\" name=\"".($it == "RESULT1" ? "r1" : "r2")."_$c\" value=\"{$row[$it]}\" data-default=\"{$row[$it]}\"/></td>";
			}
			else {
				echo "<td>{$row[$it]}</td>";
			}
		}
		echo "</tr>";
		$c++;
	}
	echo "</table><input type=\"submit\" value=\"speichern\"></form>";
	
	$sql = "SELECT ID, FullName FROM competition_teams;";
	$result = mysqli_query($mySql, $sql);
	echo "<table>";
	while ($row = mysqli_fetch_assoc($result)) {
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