<?php
namespace TableFootball\League\Services;

use TableFootball\League\Core\DbProvider;
use TableFootball\League\Exceptions\DatabaseException;

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

    public function getLeagueMatches($leagueId)
    {
        $sql = 'SELECT * FROM `matches` WHERE `league_id` = :leagueId';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['leagueId' => $leagueId]);
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
}
