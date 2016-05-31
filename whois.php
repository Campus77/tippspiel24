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
		
		// remove old bet
		$sql = "SELECT * FROM competition_users";

		$result = mysqli_query($mySql, $sql);
		echo "<table>";
		while ($row = mysqli_fetch_row($result)){
			echo "<tr>";
			foreach ($row as $entry) {
				echo "<td>".$entry."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
?>