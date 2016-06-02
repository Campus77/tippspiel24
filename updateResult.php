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
 <?php
	$ini_array = parse_ini_file("config.ini", TRUE);
	$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	function GetContentFromUrl($url) {
		$f = fopen($url, "rb");
		$c = stream_get_contents($f);
		fclose($f);

		return $c;
	}

	function GetResult($content, $row) {
//		$BASE_URL = "https://footballdb.herokuapp.com/api/v1/event/world.2014/";
//		$roundsUrl = $BASE_URL ."rounds";
//		$roundUrl = $BASE_URL ."round/3";

		$months = ("Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "...");
		// build String
		$dateToSearch = $row['DAY'].". ".$months[$row['MONTH']]." ".$row['YEAR'];
		return $dateToSearch;
	
	}

	
	// fetch remote page
	$URL = "http://de.wikipedia.org/wiki/Fu%C3%9Fball-Weltmeisterschaft_2014";
	$c = GetContentFromUrl($URL);

	// TODO: get all finished matches (Anpfiff + 105min) with null results from DB
	$sql = "SELECT p.RESULT,
				DATE_FORMAT(p.Anpfiff - INTERVAL 5 HOUR, '%d') AS DAY,
				DATE_FORMAT(p.Anpfiff - INTERVAL 5 HOUR, '%m') AS MONTH,
				DATE_FORMAT(p.Anpfiff - INTERVAL 5 HOUR, '%Y') AS YEAR,
				DATE_FORMAT(p.Anpfiff, '%H:%i') AS MESZ,

				t1.FullName AS TEAM1, t2.FullName AS TEAM2,
				r.RESULT1, r.RESULT2
				FROM competition_plan p
				LEFT JOIN competition_results r
				ON r.ID = p.RESULT
				LEFT JOIN competition_teams t1
				ON r.TEAM1 = t1.ID
				LEFT JOIN competition_teams t2
				ON r.TEAM2 = t2.ID
				ORDER BY p.Anpfiff, p.RESULT ASC;";

	$result = mysqli_query($mySql, $sql);
	$items = Array("RESULT", "DAY", "MONTH", "YEAR", "MESZ", "TEAM1", "TEAM2", "RESULT1", "RESULT2");
	echo "<table><tr>";
	foreach ($items as $it) {
		echo "<td>$it</td>";
	}
	echo "<td>REMOTE1</td><td>REMOTE2</td></tr>";

	while ($row = mysqli_fetch_assoc($result)) {
		echo "<tr>";
		foreach ($items as $it) {
			echo "<td>{$row[$it]}</td>";
		}
		$REMOTERES = explode(':', GetResult($c, $row));
		echo "<td>".$REMOTERES[0]."</td><td>".$REMOTERES[1]."</td>";
		echo "</tr>";
	}
	echo "</table>";

	// TODO: store results
	

//	$start = round(microtime(true) * 1000);
//	GetResult("", "", "");
//	$end = round(microtime(true) * 1000);
	
//		echo $end - $start;

//	$sql = "UPDATE competition_results SET RESULT1=$r1, RESULT2=$r2 WHERE ID=$gid;";
//	mysqli_query($mySql, $sql);
?>