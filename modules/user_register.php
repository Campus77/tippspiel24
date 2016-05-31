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
if (md5($_POST["password"]) != md5($_POST["password2"]))
{
    printf("Passwords are not identical :-(\n");
	session_destroy();
	exit();
}
$ini_array = parse_ini_file("../config.ini", TRUE);
$mySql = new mysqli($ini_array["database"]["host"], $ini_array["database"]["user"], $ini_array["database"]["pwd"], $ini_array["database"]["db"]);

/* check connection */
if (mysqli_connect_errno()) {
	die('Connect failed: ' . mysqli_connect_error());
	session_destroy();
    exit();
}
$mail = mysqli_real_escape_string($mySql, $_POST["Email"]);
$pwd = md5(mysqli_real_escape_string($mySql, $_POST["password"]));
$user = mysqli_real_escape_string($mySql, $_POST["username"]);

// check if user exists

$sql="SELECT COUNT(*) FROM competition_users WHERE Username LIKE '".$user."';";
if (mysqli_fetch_row(mysqli_query($mySql, $sql))[0] > 0) {
	// user already exists
	header("Location: ../index.php?error=reg_user_exists");
	exit;
}

$sql="INSERT INTO competition_users (EMail, Password, Username) VALUES ('$mail', '$pwd', '$user')";
if (! mysqli_query($mySql, $sql)) {
  die('Error: ' . mysqli_error($mySql));
  session_destroy();
}

$sql="SELECT ID FROM competition_users WHERE Username LIKE '$user';";
$result = mysqli_query($mySql, $sql);
if (!$result) {
  die('Error: ' . mysqli_error($mySql));
  session_destroy();
}

$row = mysqli_fetch_assoc($result);
$mySql->close();

$_SESSION["activeUser"] = $user;
$_SESSION["activeUserId"] = $row['ID'];
header('Location: ../ranking.php');

?>
