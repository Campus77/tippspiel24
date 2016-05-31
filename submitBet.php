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
	session_start();
	if (isset($_SESSION["activeUser"]) and isset($_POST["game"]) and isset($_POST["bet1"]) and isset($_POST["bet2"]))
	{
		$gid = $_POST['game'];
		$b1 = $_POST["bet1"];
		$b2 = $_POST["bet2"];

		$ini_array = parse_ini_file("config.ini", TRUE);
		$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		$uid = $_SESSION['activeUserId'];

		// check timestamp
		$allowed = mysqli_fetch_row(mysqli_query($mySql, "SELECT Anpfiff > NOW() AS allowed FROM competition_plan WHERE RESULT = $gid"))[0];
		if (!$allowed) exit;
		
		// remove old bet
		$sql = "DELETE FROM competition_bets WHERE USER_ID=$uid AND RESULT_ID=$gid;";

		$result = mysqli_query($mySql, $sql);
		if (!$result) {
		  die('Error: ' . mysqli_error($mySql));
		}
		
		// add new bet
		$sql = "INSERT INTO competition_bets (USER_ID, RESULT_ID, BET1, BET2) VALUES ($uid, $gid, $b1, $b2);";
		$result = mysqli_query($mySql, $sql);
		if (!$result) {
		  die('Error: ' . mysqli_error($mySql));
		}
	}
?>