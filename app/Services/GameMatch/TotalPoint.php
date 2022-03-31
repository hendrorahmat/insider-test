<?php

namespace App\Services\GameMatch;

use App\Entities\Club;
use App\Entities\ClubMatch;
use App\Repositories\Eloquent\EloquentLeagueRepository;
use App\Repositories\GroupLeagueRepository;
use App\Services\Contracts\PredictionGameRule;

class TotalPoint implements PredictionGameRule
{
    /**
     * @var GroupLeagueRepository
     */
    private $groupLeagueRepository;

    /**
     * @var ClubMatch
     */
    private $clubMatch;

    public function __construct(ClubMatch $clubMatch, GroupLeagueRepository $groupLeagueRepository)
    {
        $this->groupLeagueRepository = $groupLeagueRepository;
        $this->clubMatch = $clubMatch;
    }

    /**
     * @return Club|null
     */
    public function getClubWinner(): ?Club
    {
        $league = new EloquentLeagueRepository();
        $championLeague = $league->findChampionLeague();

        $data = $this->groupLeagueRepository->findHighestPointClubByClubIds([
            $this->clubMatch->getHome()->getId(),
            $this->clubMatch->getAway()->getId()
        ], $championLeague->id);

        if ($data->getStanding()->getTotalPoints() === 0) {
            return null;
        }
        return $data;
    }

    /**
     * @return int
     */
    public function getPoint(): int
    {
        return 10;
    }
}
