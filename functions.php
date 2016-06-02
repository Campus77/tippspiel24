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

	function sign( $number ) {
		return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 );
	} 

	function CalcScoreForMatch($bet1, $bet2, $result1, $result2) {

		if (is_null($bet1) || is_null($bet2) || is_null($result1) || is_null($result2))
			return null;
			
		$diffBet = $bet1 - $bet2;
		$diffResult = $result1 - $result2;
		
		// Ergebnis: 4
		if (($bet1 == $result1) && ($bet2 == $result2))
			return 4;
		// Differenz: 3
		if ($diffBet == $diffResult)
			return 3;
		// Tendenz: 2
		if (sign($diffBet) == sign($diffResult))
			return 2;
		// Niete: 0
		return 0;
	}
	
	function CalcScoreForBonusTeam($bet, $result, $points, $ordered) {
		$subBets = explode(';', $bet);
		$subResults = explode(';', $result);
		
		if (count($subResults) != count($subBets)) {
			return null;
		}
		$subScores = array();
		if ($ordered) {
			for ($i = 0; $i < count($subBets); ++$i) {
				$subScores[] = ($subBets[$i] == $subResults[$i] ? $points : 0);
			}
		} else {
			for ($i = 0; $i < count($subBets); ++$i) {
				$subScores[] = (in_array($subBets[$i], $subResults) ? $points : 0);
			}
		}
		return implode(';', $subScores);
	}
	
	function CalcScoreForBonus($bet, $result, $type, $points) {
		if (is_null($bet) || is_null($result)) {
			return null;
		}
		switch ($type) {
			case 'TEAM':
				return CalcScoreForBonusTeam($bet, $result, $points, false);
			case 'TEAM2':
				return CalcScoreForBonusTeam($bet, $result, $points, false);
			case 'TEAM4':
				return CalcScoreForBonusTeam($bet, $result, $points, false);
			case 'TEAM8GROUP':
				return CalcScoreForBonusTeam($bet, $result, $points, true);
			case 'INT':
				$diff = abs($bet - $result);
				return max(0, 10 - $diff);
			default:
				return null;
		}
	}
	
	function FormatValue($v, $charCount, $hidden) {
		return (is_null($v) ? str_repeat('-', $charCount) : ($hidden ? str_repeat('*', $charCount) : $v));
	}
	
	function CalculateScoreForBonus($bet, $result, $type, $points)
	{
		if (is_null($bet) || is_null($results))
			return null;

		switch ($type) {
			case 'TEAM': 
				return ($bet == $result ? $points : 0);
				break;
			default:
				return null;
		}
	}
?>