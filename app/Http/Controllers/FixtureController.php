<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFixtureRequest;
use App\Repositories\GroupLeagueRepository;
use App\Repositories\GroupRepository;
use App\Services\FixtureService;
use App\Services\MatchService;

class FixtureController extends Controller
{
    /**
     * @var FixtureService $fixtureService
     */
    private $fixtureService;

    /**
     * @var MatchService
     */
    private $matchService;

    /**
     * @var GroupLeagueRepository
     */
    private $groupLeagueRepository;

    public function __construct(
        FixtureService        $fixtureService,
        GroupLeagueRepository $groupLeagueRepository,
        MatchService $matchService
    )
    {
        $this->fixtureService = $fixtureService;
        $this->groupLeagueRepository = $groupLeagueRepository;
        $this->matchService = $matchService;
    }

    public function store(CreateFixtureRequest $createFixtureRequest)
    {
        $groupId = intval($createFixtureRequest->get('group_id'));
        $groupEntity = $this->groupLeagueRepository->getClubsGroupedByGroupId($groupId);
        $this->fixtureService->generateFixtures($groupEntity);
        $matches = $this->matchService->getMatchByGroupId($groupId);

        return view('groups.match', compact('matches', 'groupId'));
    }
}
