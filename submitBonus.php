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
	if (isset($_SESSION["activeUser"]))
	{
		$uid = $_SESSION['activeUserId'];

		$ini_array = parse_ini_file("config.ini", TRUE);
		$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		// get questions
		$sql = "SELECT ID, QUESTION, TYPE FROM competition_bonus ORDER BY competition_bonus.ID ASC;";
		$result = mysqli_query($mySql, $sql);
		if (!$result) {
		  die('Error: ' . mysqli_error($mySql));
		}
		
		function handleSql($bid, $res)
		{
			global $uid, $mySql;
			
			// check timestamp
			$allowed = mysqli_fetch_row(mysqli_query($mySql, "SELECT BET_LIMIT > NOW() AS allowed FROM competition_bonus WHERE ID = $bid"))[0];
			if (!$allowed) return;

			// remove old bet
			$sql = "DELETE FROM competition_bonus_bets WHERE USER_ID=$uid AND BONUS_ID=$bid;";

			$result = mysqli_query($mySql, $sql);
			if (!$result) {
			  die('Error: ' . mysqli_error($mySql));
			}
			
			// add new bet
			$sql = "INSERT INTO competition_bonus_bets (USER_ID, BONUS_ID, BONUS_BET) VALUES ($uid, $bid, ".(is_null($res) ? "null" : "\"$res\"").");";
			$result = mysqli_query($mySql, $sql);
			if (!$result) {
			  die('Error: ' . mysqli_error($mySql));
			}
		}
		
		function formResult($id, $size) {
			global $_POST;
			$a = Array();
			for ($i = 1; $i <= $size; ++$i) {
				$item = $_POST["b$id"."_".$i];
				if ($item == "---" or $item == "") $item = null;
				$a[] = $item;
			}
			return implode(";", $a);
		}
		
		// process each question
		while ($row = mysqli_fetch_assoc($result))
		{
			$res = 'null';
			$id = $row['ID'];
			switch ($row['TYPE']) {
				case 'TEAM':
					$res = formResult($id, 1);
					break;
				case 'TEAM2':
					$res = formResult($id, 2);
					break;
				case 'TEAM4':
					$res = formResult($id, 4);
					break;
				case 'TEAM8GROUP':
					$res = formResult($id, 8);
					break;
				case 'INT':
					$res = formResult($id, 1);
					break;
				default:
					break;
			}
			handleSQL($row['ID'], $res);
		}
		header("Location: bonus.php");
	}
?>