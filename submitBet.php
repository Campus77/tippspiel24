 <?php
/**
* This file is part of tippspiel24.
* 
* tippspiel24 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*  
* tippspiel24 is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with tippspiel24.  If not, see <http://www.gnu.org/licenses/>.
*/
	require_once('database.php');
	session_start();
	if (isset($_SESSION["activeUser"]) and isset($_POST["match"]) and isset($_POST["bet1"]) and isset($_POST["bet2"]))
	{
		$userid = $_SESSION['activeUserId'];
		$matchid = $_POST['match'];
		$bet1 = $_POST["bet1"];
		$bet2 = $_POST["bet2"];
		
		$db = Database::getInstance();
		
		if( ! $db->storeBet($userid, $matchid, $bet1, $bet2)) {
			exit;
		}
	}
?>