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
<html>
<head>
<meta name="author" content="Marcel Daneyko">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link href="css/competition.css" rel="stylesheet" type="text/css" />
	<link href="css/flags.css" rel="stylesheet" type="text/css" />
	<link href='fonts/roboto.css' rel="stylesheet" type="text/css" />
	<title>WM 2018</title>
</head>
<body>
<?php
require_once ('database.php');

$url = "https://www.openligadb.de/api/getmatchdata/fifa18";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_TIMEOUT, 90);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);

$matches = json_decode($output, true);

$matchid = -1;
$lastFinishedGameId = 0;
foreach ($matches as $match) 
{
	if (true == $match["MatchIsFinished"])
	{
		#$lastFinishedGameId = $match["MatchID"];		
		$matchid++;
	}
	else
	{
		#echo("Not finished: " . $match["MatchID"]);
		break;
	}
}

$p1 = 0;
$p2 = 0;
foreach ($matches[$matchid]["MatchResults"] as $result) 
{
	if (2 == $result["ResultOrderID"])
	{
		$p1 = $result["PointsTeam1"];
		$p2 = $result["PointsTeam2"];
		break;
	}	
}

$db = Database::getInstance();
if ($db->updateMatchResult($matchid+1, $p1, $p2))
{
?>
    <div id="title">
    <h2><?php echo($matches[$matchid]["Team1"]["TeamName"] . " " . $p1); ?> : <?php echo($p2 . " " . $matches[$matchid]["Team2"]["TeamName"]); ?></h2>
    </div>
<?php
}
$db->updateScoredGoals();

?>
</body>
</html>