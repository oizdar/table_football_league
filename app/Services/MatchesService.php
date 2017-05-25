<?php
namespace TableFootball\League\Services;

use TableFootball\League\Core\DbProvider;
use TableFootball\League\Exceptions\DatabaseException;
use TableFootball\League\Exceptions\InvalidArgumentException;

class MatchesService
{
    /** @var \PDO */
    protected $db;

    public function __construct()
    {
        $this->db = DbProvider::getInstance()->getConnection();
    }

    public function renderMatchesSchedule(int $leagueId, array $players)
    {
        $pairs = $this->combinePairs($players);
        $this->insertMatches($leagueId, $pairs);
        return $pairs;
    }

    protected function combinePairs(array $players)
    {
        $teams = [];
        $playersNumber = count($players);
        $ATeam[] = $players[0];
        for($x = 1; $x < $playersNumber; $x++) {
            $ATeam[1] = $players[$x];
            $playersLeft = array_values(array_diff($players, $ATeam));
            $playersLeftNumber = count($playersLeft);
            for($y = 0; $y < $playersLeftNumber-1; $y++) {
                $BTeam[0] = $playersLeft[$y];
                for($z = $y+1; $z<$playersLeftNumber; $z++) {
                    $BTeam[1] = $playersLeft[$z];
                    $teams[] = array_merge($ATeam, $BTeam);
                }
                $BTeam = [];
            }
        }
        return $teams;
    }

    protected function insertMatches(int $leagueId, array $matchesPairs)
    {
        $dataToInsert = [];
        foreach($matchesPairs as $data) {
            $dataToInsert[] = $leagueId;
            foreach($data as $value) {
                $dataToInsert[] = $value;
            }
        }

        $numberOfMatches = count($matchesPairs);
        $sql = 'INSERT INTO `matches` 
            (`league_id`, `team_1_player_1`, `team_1_player_2`, `team_2_player_1`, `team_2_player_2`) 
            VALUES (?, ?, ?, ?, ?)';
        $sql .= str_repeat(', (?, ?, ?, ?, ?)', $numberOfMatches-1);

        $stmt = $this->db->prepare($sql);

        if(!$stmt->execute($dataToInsert)) {
            throw new DatabaseException('Database error occurred, try again later. If error repeats contact administrator.');
        }
    }

    public function getLeagueMatches(int $leagueId)
    {
        $sql = 'SELECT * FROM `matches` WHERE `league_id` = :leagueId';
        $stmt = $this->db->prepare($sql);
        if(!$stmt->execute(['leagueId' => $leagueId])) {
            throw new DatabaseException('Database error occurred, try again later. If error repeats contact administrator.');
        };
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $parsedResult = [];
        foreach ($result as $row) {
            $parsedRow = [
                'id' => $row->id,
                'teams' => [
                    'TeamA' => [
                        'PlayerA' => $row->team_1_player_1,
                        'PlayerB' => $row->team_1_player_2
                    ],
                    'TeamB' => [
                        'PlayerA' => $row->team_2_player_1,
                        'PlayerB' => $row->team_2_player_2
                    ]
                ]
            ];
            if ($row->team_1_score === null || $row->team_2_score === null) {
                $parsedRow['score'] = null;
            } else {
                $parsedRow['score'] = $row->team_1_score . ' - ' . $row->team_2_score;
            }
            $parsedResult[] = $parsedRow;
        }
        return $parsedResult;
    }

    public function updateMatchScore(int $leagueId, int $matchId, string $scoreId)
    {
        if(!$this->isMatchExists($leagueId, $matchId)) {
            throw new InvalidArgumentException("Match for leagueId: \"$leagueId\" with id: \"$matchId\" doesn't exists.");
        };

        $matchScores = preg_split('/(-|:)/', $scoreId);
        if((int)$matchScores[0] === (int)$matchScores[1]) {
            throw new InvalidArgumentException('Draws are not allowed. Please play extra time. ');
        };
        $sql = 'UPDATE `matches` 
            SET `team_1_score` = :scoreLeft, `team_2_score` = :scoreRight
            WHERE `id` = :matchId';

        $stmt = $this->db->prepare($sql);
        if(!$stmt->execute(['scoreLeft' => $matchScores[0], 'scoreRight' => $matchScores[1], 'matchId' => $matchId])) {
            throw new DatabaseException('Database error occurred, try again later. If error repeats contact administrator.');
        };

    }

    protected function isMatchExists(int $leagueId, int $matchId)
    {
        $sql = 'SELECT COUNT(*) FROM `matches` 
            WHERE `id` = :matchId
            AND `league_id` = :leagueId';

        $stmt = $this->db->prepare($sql);
        if(!$stmt->execute(['leagueId' => $leagueId, 'matchId' => $matchId])) {
            throw new DatabaseException('Database error occurred, try again later. If error repeats contact administrator.');
        };

        if($stmt->fetchColumn() == 1) {
            return true;
        }

        return false;
    }

    public function getMatch(int $leagueId, int $matchId)
    {
        $sql = 'SELECT * FROM `matches` WHERE `league_id` = :leagueId AND `id` = :matchId';
        $stmt = $this->db->prepare($sql);
        if(!$stmt->execute(['leagueId' => $leagueId, 'matchId' => $matchId])) {
            throw new DatabaseException('Database error occurred, try again later. If error repeats contact administrator.');
        };

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getScores(int $leagueId)
    {
        $sql = 'SELECT * FROM `matches` 
            WHERE league_id = :leagueId 
            AND team_1_score IS NOT NULL
            AND team_2_score IS NOT NULL';

        $stmt = $this->db->prepare($sql);
        if(!$stmt->execute(['leagueId' => $leagueId])) {
            throw new DatabaseException('Database error occurred, try again later. If error repeats contact administrator.');
        };
        $matches = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $players = $this->getLeaguePlayers($leagueId);
        return $this->renderScores($players, $matches);
    }

    protected function getLeaguePlayers($leagueId)
    {
        $sql = 'SELECT DISTINCT `team_1_player_1`, `team_1_player_2`  FROM `matches` 
            WHERE league_id = :leagueId';
        $stmt = $this->db->prepare($sql);
        if(!$stmt->execute(['leagueId' => $leagueId])) {
            throw new DatabaseException('Database error occurred, try again later. If error repeats contact administrator.');
        };

        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $players = [];
        if(count($result) > 0) {
            foreach($result as $row) {
                $players[] = $row->team_1_player_2;
            }

            $players[] = $row->team_1_player_1;
        }
        return $players;
    }

    protected function renderScores(array $players, array $matches)
    {
        $scores = [];
        foreach($players as $player) {
            $scores[$player] = ['points' => 0, 'matches' => 0];
        }
        foreach($matches as $match) {
            if($match->team_1_score > $match->team_2_score) {
                $scores[$match->team_1_player_1]['points']++;
                $scores[$match->team_1_player_2]['points']++;
            } else {
                $scores[$match->team_2_player_1]['points']++;
                $scores[$match->team_2_player_2]['points']++;
            }
            $scores[$match->team_1_player_1]['matches']++;
            $scores[$match->team_1_player_2]['matches']++;
            $scores[$match->team_2_player_1]['matches']++;
            $scores[$match->team_2_player_2]['matches']++;
        }
        uasort($scores, function($a, $b){return $b['points']-$a['points'];});
        return $scores;
    }


}
