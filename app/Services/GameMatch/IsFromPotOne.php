<?php

namespace App\Services\GameMatch;

use App\Entities\Club;
use App\Entities\ClubMatch;
use App\Repositories\PotsClubRepository;
use App\Services\Contracts\PredictionGameRule;

/**
 * if the club from pot one, that means the club is the champion of euro league, the highest ranking from local club
 */
class IsFromPotOne implements PredictionGameRule
{
    /**
     * @var ClubMatch
     */
    private $clubMatch;

    public function __construct(ClubMatch $clubMatch, PotsClubRepository $potsClubRepository)
    {
        $this->clubMatch = $clubMatch;
        $this->potsClubRepository = $potsClubRepository;
    }

    /**
     * @return Club|null
     */
    public function getClubWinner(): ?Club
    {
        $clubHome = $this->potsClubRepository->findOneWhere([
            'pots_id' => 1,
            'club_id' => $this->clubMatch->getHome()->getId(),
        ]);

        $clubAway = $this->potsClubRepository->findOneWhere([
            'pots_id' => 1,
            'club_id' => $this->clubMatch->getAway()->getId(),
        ]);


        if (($clubAway && $clubHome) || (is_null($clubHome) && is_null($clubAway))) {
            return null;
        }

        if ($clubAway && !$clubHome) {
            return $this->clubMatch->getAway();
        }

        if ($clubHome && !$clubAway) {
            return $this->clubMatch->getHome();
        }
        return null;
    }

    /**
     * @return int
     */
    public function getPoint(): int
    {
        return 40;
    }
}
