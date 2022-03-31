<?php

namespace App\Http\Controllers;

use App\Repositories\GroupLeagueRepository;
use App\Repositories\GroupMatchRepository;
use App\Services\ChampionshipPredictionService;
use App\Services\MatchService;

class GroupSimulationController extends Controller
{
    /**
     * @var MatchService
     */
    private $matchService;

    /**
     * @var GroupLeagueRepository
     */
    private $groupLeagueRepository;

    public function __construct(
        MatchService                  $matchService,
        GroupLeagueRepository         $groupLeagueRepository,
        GroupMatchRepository          $groupMatchRepository,
        ChampionshipPredictionService $championshipPredictionService
    )
    {
        $this->matchService = $matchService;
        $this->groupLeagueRepository = $groupLeagueRepository;
        $this->groupMatchRepository = $groupMatchRepository;
        $this->championshipPredictionService = $championshipPredictionService;
    }

    public function index(int $groupId)
    {
        $match = $this->matchService->findCurrentMatch($groupId);
        $groupChampion = $this->groupLeagueRepository->getClubsGroupedByGroupId($groupId);
        $matchIds = $this->groupMatchRepository->findMatchThatNotPlayedByGroupId($groupId)
            ->pluck('match_id')
            ->toArray();
        $uuids = $this->matchService->getMatchRepository()
            ->findWhereInDistinct('match_uuid', 'id', $matchIds)
            ->pluck('match_uuid');
        $histories = $this->matchService->getResultMatchByGroupId($groupId);
        $clubPredictions = $this->championshipPredictionService->getPredictionByGroupId($groupId);

        return view('groups.simulation', compact(
            'match',
            'groupChampion',
            'uuids',
            'histories',
            'clubPredictions'
        ));
    }
}
