<?php

namespace App\Services\GameMatch;

use App\Entities\Club;
use App\Entities\ClubMatch;
use App\Repositories\Eloquent\EloquentLeagueRepository;
use App\Repositories\GroupLeagueRepository;
use App\Services\Contracts\PredictionGameRule;

class Goal implements PredictionGameRule
{
    /**
     * @var ClubMatch
     */
    private $clubMatch;

    /**
     * @var GroupLeagueRepository
     */
    private $groupLeagueRepository;

    public function __construct(ClubMatch $clubMatch, GroupLeagueRepository $groupLeagueRepository)
    {
        $this->clubMatch = $clubMatch;
        $this->groupLeagueRepository = $groupLeagueRepository;
    }

    /**
     * @return Club
     */
    public function getClubWinner(): Club
    {
        $league = new EloquentLeagueRepository();
        $championLeague = $league->findChampionLeague();

        $home = $this->groupLeagueRepository->findOneWhere([
            'league_id' => $championLeague->id,
            'club_id' => $this->clubMatch->getHome()->getId(),
        ]);

        $away = $this->groupLeagueRepository->findOneWhere([
            'league_id' => $championLeague->id,
            'club_id' => $this->clubMatch->getAway()->getId(),
        ]);

        if ($home->goal_difference > $away->goal_difference) {
            return $this->clubMatch->getHome();
        } else {
            return $this->clubMatch->getAway();
        }
    }

    /**
     * @return int
     */
    public function getPoint(): int
    {
        return 15;
    }
}
