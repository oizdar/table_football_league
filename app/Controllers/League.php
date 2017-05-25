<?php

namespace TableFootball\League\Controllers;

use TableFootball\League\Core\AbstractController;
use TableFootball\League\Core\Response;
use TableFootball\League\Exceptions\InvalidArgumentException;
use TableFootball\League\Helpers\ValidationHelper;
use TableFootball\League\Services\LeaguesService;
use TableFootball\League\Services\MatchesService;

class League extends AbstractController
{
    protected $leaguesService;
    protected $matchesService;

    public function __construct()
    {
        $this->leaguesService = new LeaguesService();
        $this->matchesService = new MatchesService();
        parent::__construct();
    }

    public function createLeague()
    {
        $params = $this->request->getParams();

        $requiredParams = ['name', 'description', 'players'];
        ValidationHelper::checkRequiredFields($requiredParams, $params);
        ValidationHelper::checkStringLength(100, $params['name'], 'name');
        ValidationHelper::checkStringLength(255, $params['description'], 'description');
        ValidationHelper::checkPlayerNames($params['players']);

        $leagueId = $this->leaguesService->addLeague($params['name'], $params['description']);
        $this->matchesService->renderMatchesSchedule($leagueId, $params['players']);

        return new Response(201, $this->leaguesService->getOne($leagueId));
    }

    public function getList()
    {
        return new Response(200, $this->leaguesService->getAll());
    }

    public function updateScore(int $leagueId, int $matchId)
    {
        $score = trim($this->request->getParam('score'));
        if($score === null) {
            throw new InvalidArgumentException('Field score is required and should not be empty');
        }
        ValidationHelper::checkScoreFormat($score);
        $this->matchesService->updateMatchScore($leagueId, $matchId, $score);
        $match = $this->matchesService->getMatch($leagueId, $matchId);
        return new Response(200, $match);
    }

    public function getScores(int $leagueId)
    {
        $scores = $this->matchesService->getScores($leagueId);

        return new Response(200, $scores);
    }

    public function getMatches($leagueId)
    {
        $matches = $this->matchesService->getLeagueMatches((int)$leagueId);
        return new Response(200, $matches);
    }
}
