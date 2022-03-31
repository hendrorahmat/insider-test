<?php

namespace App\Services;

use App\Entities\Club;
use App\Entities\ClubMatch;
use App\Services\Contracts\PredictionGameRule;

class GameService
{
    /**
     * @var ClubMatch
     */
    private $clubMatch;

    /**
     * @var PredictionGameRule[] $predictions
     */
    private $predictions = [];

    /**
     * @var Club
     */
    private $looser;

    /**
     * @var int
     */
    private $totalPoints = 0;

    public function __construct(ClubMatch $clubMatch)
    {
        $this->clubMatch = $clubMatch;
    }

    /**
     * @return Club|null if null means it draw
     */
    public function getTheWinner(): ?Club
    {
        $totalPoints = 0;
        foreach ($this->predictions as $prediction) {
            $totalPoints += $prediction->getPoint();

            if (is_null($prediction->getClubWinner())) {
                $this->clubMatch->addAwayPoint($prediction->getPoint());
                $this->clubMatch->addHomePoint($prediction->getPoint());
                continue;
            }

            if ($prediction->getClubWinner()->getId() === $this->clubMatch->getHome()->getId()) {
                $this->clubMatch->addHomePoint($prediction->getPoint());
            } else {
                $this->clubMatch->addAwayPoint($prediction->getPoint());
            }
        }

        $this->setTotalPoints($totalPoints);
        $homePercentage = round($this->clubMatch->getHomePoint() / $totalPoints * 100);
        $awayPercentage = round($this->clubMatch->getAwayPoint() / $totalPoints * 100);

        if ($homePercentage === $awayPercentage) {
            return null;
        }

        if ($homePercentage > $awayPercentage) {
            $this->setLooser($this->clubMatch->getAway());
            return $this->clubMatch->getHome();
        } else {
            $this->setLooser($this->clubMatch->getHome());
            return $this->clubMatch->getAway();
        }
    }

    /**
     * @return int
     */
    public function getTotalPoints(): int
    {
        return $this->totalPoints;
    }

    /**
     * @param int $totalPoints
     * @return GameService
     */
    public function setTotalPoints(int $totalPoints): GameService
    {
        $this->totalPoints = $totalPoints;
        return $this;
    }

    /**
     * @param PredictionGameRule ...$percentageGameRules
     * @return GameService
     */
    public function addParameterPrediction(PredictionGameRule ...$percentageGameRules): GameService
    {
        $this->predictions = array_merge($this->predictions, $percentageGameRules);

        return $this;
    }

    /**
     * @return Club|null
     */
    public function getLooser(): ?Club
    {
        return $this->looser;
    }

    /**
     * @param Club $looser
     * @return GameService
     */
    public function setLooser(Club $looser): GameService
    {
        $this->looser = $looser;
        return $this;
    }

}
