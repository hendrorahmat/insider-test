<?php

namespace App\Services\GameMatch;

use App\Entities\Club;
use App\Entities\ClubMatch;
use App\Services\Contracts\PredictionGameRule;

class HomeAway implements PredictionGameRule
{
    /**
     * @var ClubMatch
     */
    private $clubMatch;

    public function __construct(ClubMatch $clubMatch)
    {
        $this->clubMatch = $clubMatch;
    }

    /**
     * @return Club
     */
    public function getClubWinner(): Club
    {
        return $this->clubMatch->getHome();
    }

    /**
     * @return int
     */
    public function getPoint(): int
    {
        return 40;
    }
}
