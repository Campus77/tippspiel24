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
if (isset($_POST["username"]) and isset($_POST["password"]))
{
	$user = $_POST["username"];
	$pwd = md5($_POST["password"]);

	$ini_array = parse_ini_file("../config.ini", TRUE);	
	$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

	/* check connection */
	if ($mySql->connect_error) {
		echo('Connect Error (' . $mySql->connect_errno . ') ' . $mySql->connect_error);
		exit;
	}

	$sql="SELECT ID, Password FROM competition_users WHERE Username='$user';";
	$result = $mySql->query($sql);
	if (!$result) {
	  die('Error: ' . mysqli_error($mySql));
	  session_destroy();
	  exit;
	}	

	$row = mysqli_fetch_assoc($result);
	$mySql->close();
	
	if ($row['Password'] == $pwd)
	{
	   session_start();
		
	   $_SESSION["activeUser"] = $user;
	   $_SESSION["activeUserId"] = $row['ID'];
	   //error_reporting(E_ALL | E_WARNING | E_NOTICE);
	   //ini_set('display_errors', TRUE);
		
	   header('Location: ../ranking', true, 301);
	   exit;
	}
	else
	{
		header('Location: /', true, 301);
		exit;
	}
}
else
{
   exit("Variablen nicht gesetzt!");
}
?>