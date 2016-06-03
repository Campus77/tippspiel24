<?php

require_once('databaseconfig.php');

class Database {
		
    private $_connection;
    private static $_instance; //The single instance

    private $_host = DatabaseConfig::HOST;
    private $_username = DatabaseConfig::USER;
    private $_password = DatabaseConfig::PASS;
    private $_database = DatabaseConfig::NAME;

    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    // Constructor
    private function __construct()
    {
        try {
            $this->_connection  = new \PDO("mysql:host=$this->_host;dbname=$this->_database;charset=utf8", $this->_username, $this->_password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    // Magic method clone is empty to prevent duplication of connection
    private function __clone()
    {
    }
	
	private function execute($sql, $data = array())
	{
		$stmt = $this->_connection->prepare($sql);
		$i = 0;
		foreach ($data as $value)
		{
			$type = is_string($value) ? PDO::PARAM_STR : PDO::PARAM_INT;
			$stmt->bindValue(++$i, $value, $type);
		}
		if (!$stmt->execute()) {
			var_dump($stmt->errorInfo());
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}	

/*********************************************************************************************/
	
	public function checkCredentials($user, $pwd)
	{
		$sql = "SELECT id FROM competition_users WHERE username = ? AND password = ?";
		$res = $this->execute($sql, array($user, md5($pwd)));
		// var_dump($res, $user, $pwd, md5($pwd));
		// exit;

		if (count($res) == 1) {
			return $res[0]['id'];
		}
		return FALSE;
	}
	
    public function getServerTime()
    {
		$res = $this->execute("SELECT DATE_FORMAT(NOW(), \"%Y-%m-%dT%H:%i:%s\") AS ts");
		return $res[0]['ts'];
    }

	public function getBetsForUser($uid)
	{
		$sql = "SELECT
					m.id,
					DATE_FORMAT(m.kickoff, '%d.%m.%Y %H:%i') AS kickoff,
					(NOW() < m.kickoff) AS open,
					m.result1,
					m.result2,
					l.name as location,
					t1.ShortName AS tsn1, t1.FullName AS tfn1, t1.Flag AS tflg1,
					t2.ShortName AS tsn2, t2.FullName AS tfn2, t2.Flag AS tflg2,
					b.bet1, b.bet2
					FROM competition_matches m
					JOIN competition_locations l ON m.location_id = l.id
					JOIN competition_teams t1 ON m.team1_id = t1.id
					JOIN competition_teams t2 ON m.team2_id = t2.id
					LEFT JOIN competition_bets b ON m.id = b.match_id AND b.user_id = $uid
					ORDER BY m.kickoff, m.id ASC;";
		return $this->execute($sql);
	}
	
	public function storeBet($userid, $matchid, $bet1, $bet2)
	{
		// check timestamp for bet
		$sql = "SELECT kickoff > NOW() AS open FROM competition_matches WHERE id = ?";
		$res = $this->execute($sql, array($matchid));
		
		if ( ! $res[0]['open'])
		{
			return FALSE;
		}
		// remove old bet
		$this->execute("DELETE FROM competition_bets WHERE user_id = ? AND match_id = ?", array($userid, $matchid));

		// add new bet
		$this->execute("INSERT INTO competition_bets (user_id, match_id, bet1, bet2) VALUES (?, ?, ?, ?);",
			array($userid, $matchid, $bet1, $bet2)
		);

		return TRUE;
	}

	public function getUsers()
	{	
		$sql = "SELECT id as uid, username as name FROM competition_users";
		return $this->execute($sql);
	}
	
	public function getAllBetsForFinishedMatches()
	{
		$sql = "SELECT
				b.user_id AS uid,
				u.username AS name,
				b.bet1 as b1, b.bet2 as b2,
				m.result1 as r1, m.result2 as r2
				FROM competition_bets as b
				LEFT JOIN competition_matches AS m
				ON b.match_id = m.id
				LEFT JOIN competition_users AS u
				ON b.user_id = u.id
				WHERE NOT ISNULL(m.result1) AND NOT ISNULL(m.result2)";
		return $this->execute($sql);
	}
	
	public function getAllFinishedBonusBets()
	{
		$sql = "SELECT
				bb.user_id AS uid, u.username AS name, bb.bonus_bet, b.result, b.type, b.points
				FROM competition_bonus_bets as bb
				LEFT JOIN competition_bonus AS b
				ON bb.bonus_id = b.id
				LEFT JOIN competition_users AS u
				ON bb.user_id = u.id
				WHERE NOT ISNULL(b.result)";
		return $this->execute($sql);
	}
	
	// if matchid === FALSE: get closest match
	public function getMatch($matchid)
	{
		$sql = "SELECT
				m.id,
				m.kickoff > NOW() AS hidden,
				DATE_FORMAT(m.kickoff, '%d.%m.%Y %H:%i') AS kickoff,
				l.name AS location,
				t1.shortname AS tsn1, t1.fullname AS tfn1, t1.Flag AS tflg1,
				t2.shortname AS tsn2, t2.fullname AS tfn2, t2.Flag AS tflg2,
				m.result1, m.result2
				FROM competition_matches m
				JOIN competition_locations l
				ON l.id = m.location_id
				JOIN competition_teams t1
				ON t1.id = m.team1_id
				JOIN competition_teams t2
				ON t2.id = m.team2_id
				WHERE ";
		
		$params = array();
		if ($matchid !== FALSE)
		{
			$sql .= "m.id = ?";
			$params[] = $matchid;
		}
		else 
		{
			$sql .= "NOW() > m.kickoff ORDER BY TIMEDIFF(NOW(), m.kickoff) ASC, m.id ASC LIMIT 0, 1";
		}
		$res = $this->execute($sql, $params);
		if (count($res) == 1) {
			return $res[0];
		}
		return array();
	}
	
	public function getAllMatchIds()
	{
		$sql = "SELECT id FROM competition_matches ORDER BY kickoff ASC";
		$res = $this->execute($sql);
		$matchids = array();
		foreach ($res as $row)
		{
			$matchids[] = $row['id'];
		}
		return $matchids;
	}

	public function getAllBetsForMatch($matchid)
	{
		$sql = "SELECT
				u.id,
				username,
				bet1, bet2
				FROM competition_users u
				LEFT JOIN competition_bets b
				ON b.match_id = ?
				AND u.id = b.user_id
				ORDER BY LOWER(u.username) ASC";
		return $this->execute($sql, array($matchid));
	}
	
	public function getAllTeams()
	{
		$sql = "SELECT id, shortname, fullname, flag, ingroup FROM competition_teams WHERE ingroup IS NOT NULL ORDER BY FullName ASC";
		$teams = $this->execute($sql);
		return $teams;
	}
	
	public function getAllMatches()
	{
		$sql = "SELECT m.id, m.kickoff AS kickoff, l.name AS location,
				t1.fullname AS team1, t2.fullname AS team2,
				m.result1, m.result2
				FROM competition_matches m
				JOIN competition_locations l
				ON m.location_id = l.id
				JOIN competition_teams t1
				ON m.team1_id = t1.id
				JOIN competition_teams t2
				ON m.team2_id = t2.id
				ORDER BY m.kickoff, m.id ASC;";
		return $this->execute($sql);
	}

	public function updateMatchResult($matchid, $result1, $result2)
	{
		$sql = "UPDATE competition_matches SET result1 = ?, result2 = ? WHERE id = ?";
		$this->execute($sql, array($result1, $result2, $matchid));
	}

	
	public function getAllBonusQuestions()
	{
		$sql = "SELECT
				id, question, type, result, points
				FROM competition_bonus
				ORDER BY id";
		$res = $this->execute($sql);
		$bonus = array();
		foreach ($res as $row)
		{
			$bonus[$row['id']] = $row;
			$bonus[$row['id']]['bets'] = array();
		}
		return $bonus;
	}
	
	public function getNumGroups()
	{
		$sql = "SELECT COUNT(DISTINCT(ingroup)) AS numgroups FROM competition_teams";
		$numgroups = $this->execute($sql);
		return $numgroups[0]['numgroups'];
	}
	
	public function getBonusBetForBonus($bonusid)
	{
		$sql = "SELECT
				u.username, bb.bonus_bet
				FROM competition_users u
				LEFT JOIN competition_bonus_bets bb
				ON u.id = bb.user_id AND bb.bonus_id = ?
				ORDER BY LOWER(u.username) ASC";
		return $this->execute($sql, array($bonusid));
	}

	public function getBonusBetsForUser($userid)
	{
		$sql = "SELECT
				id, question, type, points, bonus_bet
				FROM competition_bonus b
				LEFT JOIN competition_bonus_bets bb
				ON b.id = bb.bonus_id
				AND bb.user_id = ?
				ORDER BY b.id ASC";
		return $this->execute($sql, array($userid));
	}
	
	public function areBonusBetsAllowed()
	{
		$sql = "SELECT NOW() < MIN(kickoff) AS allowed FROM competition_matches";
		$res = $this->execute($sql);
		$allowed = (count($res) == 1 && $res[0]['allowed']);
		return $allowed;
	}
	
	public function storeBonusBet($userid, $bonusid, $bonusbet)
	{
		// check timestamp
		if (!$this->areBonusBetsAllowed())
		{
			return;
		}

		// remove old bet
		$sql = "DELETE FROM competition_bonus_bets WHERE user_id = ? AND bonus_id = ?";
		$res = $this->execute($sql, array($userid, $bonusid));
		
		// add new bet
		$sql = "INSERT INTO competition_bonus_bets (user_id, bonus_id, bonus_bet) VALUES (?, ?, ?);";
		$this->execute($sql, array($userid, $bonusid, (is_null($bonusbet) ? null : $bonusbet)));
	}
	
}

?>
