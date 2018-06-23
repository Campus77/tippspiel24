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

require_once('../database.php');

if (isset($_POST['username']) and isset($_POST['password']))
{
	$user = $_POST['username'];
	$pwd = $_POST['password'];

	$db = Database::getInstance();

	$id = $db->checkCredentials($user, $pwd);
	if ($id !== FALSE)
	{
	   session_start();
		
	   $_SESSION['activeUserId'] = $id;
	   $_SESSION['activeUser'] = $db->getUsernameForId($id);
		
	   header('Location: /ranking', true, 301);
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