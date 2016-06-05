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
require_once ('../database.php');

$db = Database::getInstance();

function handleError($errCode) {
	session_destroy();
	header('Location: /?register=' . $_POST['regtoken'] . '&error=' . $errCode);
	exit();
}

session_start();
if (md5($_POST["password"]) != md5($_POST["password2"]))
{
    handleError("reg_pw_mismatch");
}

$username = $_POST["username"];
$password = md5($_POST["password"]);
$email = $_POST["email"];

// check if user exists
if ($db->usernameExists($username)) {
	handleError('reg_user_exists');
}

$id = $db->registerUser($username, $password, $email);

if ($id === FALSE) {
	handleError('reg_db_error');
}

$_SESSION["activeUser"] = $username;
$_SESSION["activeUserId"] = $id;
header('Location: /ranking');
exit;
?>
