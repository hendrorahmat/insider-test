<?php

namespace App\Services\GameMatch;

use App\Entities\Club;
use App\Entities\ClubMatch;
use App\Services\Contracts\PredictionGameRule;

class TeamStrength implements PredictionGameRule
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
        if ($this->clubMatch->getHome()->getStrength() > $this->clubMatch->getAway()->getStrength()) {
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
        return 30;
    }
}
