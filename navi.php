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
	class Navi
	{
		const PAGENAME = "EM 2016";

		static private $naviMap = Array(
				"tipps"		=>	"Tippabgabe",
				"ranking"	=>	"Bestenliste",
				"results"	=>	"Ergebnisse",
				"bonusresults"		=>	"Bonus",
				"logout"	=>	"Logout"
			);
		
		static private function getPageTitle()
		{
			return self::$naviMap[self::getPage()];
		}
		
		static private function getPage()
		{
			return basename($_SERVER['PHP_SELF'], '.php');
		}
		
		static public function getMenu()
		{
			$out = "	<div id=\"navi\"><nav><a href=\"#\" id=\"menu-icon\"></a><ul>";

			foreach (self::$naviMap as $link => $title)
				$out .= "<li id=\"elem\"><a href=\"$link.php\"><span id=\"elem\">$title</span></a></li>";

			$out .= "</ul></nav></div><div id=\"title\">
					<h1>".ucfirst($_SESSION["activeUser"])."'s EM 2016</h1>
					<h2>".self::getPageTitle()."</h2>
				</div>
				<div style=\"clear:both\"></div>";
			return $out;
		}
		
		static public function getTitle()
		{
			return self::PAGENAME." - ".self::getPageTitle();
		}
	}
?>
			
