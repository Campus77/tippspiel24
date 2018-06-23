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

session_start();
$db = Database::getInstance();
$resetpw = isset($_POST['resetpw']) ? $_POST['resetpw'] : "";
// if resetpw invalid -> redirect to login
if ($resetpw != 'chooseYourPassword') {
	session_destroy();
	header('Location: /');
	exit;
}
// check passwords
if ($_POST["password"] != $_POST["password2"]) {
	session_destroy();
	header('Location: /?resetpw=' . $_POST['resetpw'] . '&error=reg_pw_mismatch');
	exit();
}

// TODO: get userid from resetToken
// for now let's only change Jule's PW, user id = 23
$userId = 23;

$db->updatePassword($userId, $_POST["password"]);

header('Location: /');
exit;
?>
