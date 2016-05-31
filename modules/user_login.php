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
if (isset($_POST["username"]) and isset($_POST["password"]))
{
	$user = $_POST["username"];
	$pwd = md5($_POST["password"]);

	$ini_array = parse_ini_file("../config.ini", TRUE);
	$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		session_destroy();
		exit();
	}

	$sql="SELECT ID, Password FROM competition_users WHERE Username='$user';";
	$result = mysqli_query($mySql, $sql);
	if (!$result) {
	  die('Error: ' . mysqli_error($mySql));
	  session_destroy();
	}

	$row = mysqli_fetch_assoc($result);
	$mySql->close();
	
	if ($row['Password'] == $pwd)
	{
	   $_SESSION["activeUser"] = $user;
	   $_SESSION["activeUserId"] = $row['ID'];
		header('Location: ../ranking.php');
	}
	else
	{
		header('Location: ../index.php');
		session_destroy();
	}
}
else
{
   exit("Variablen nicht gesetzt!");
   session_destroy();
}
?>

