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
	require_once ('database.php');
	session_start();
	if (isset($_SESSION["activeUser"]))
	{
		$db = Database::getInstance();
		$userid = $_SESSION['activeUserId'];

		function formResult($id, $size) {
			global $_POST;
			$a = Array();
			$hasData = FALSE;
			for ($i = 1; $i <= $size; ++$i) {
				$item = $_POST["b$id"."_".$i];
				if ($item == "---" or $item == "")
				{
					$item = NULL;
				}
				else
				{
					$hasData = TRUE;
				}
				$a[] = $item;
			}
			return $hasData ? implode(";", $a) : NULL;
		}
		
		// get questions
		$bonus = $db->getAllBonusQuestions();
		$numGroups = $db->getNumGroups();

		// process each question
		foreach ($bonus as $row)
		{
			$res = NULL;
			$id = $row['id'];
			switch ($row['type']) {
				case 'TEAM':
					$res = formResult($id, 1);
					break;
				case 'TEAM2':
					$res = formResult($id, 2);
					break;
				case 'TEAM4':
					$res = formResult($id, 4);
					break;
				case 'TEAMGROUP':
					$res = formResult($id, $numGroups);
					break;
				case 'INT':
					$res = formResult($id, 1);
					break;
				default:
					break;
			}
			$db->storeBonusBet($userid, $row['id'], $res);
		}
		header("Location: /bonus");
		exit;
	}
?>