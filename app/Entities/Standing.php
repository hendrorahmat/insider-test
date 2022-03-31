<?php

namespace App\Entities;

class Standing
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $totalPoints;

    /**
     * @var int
     */
    private $match;

    /**
     * @var int
     */
    private $win;

    /**
     * @var int
     */
    private $draw;

    /**
     * @var int
     */
    private $loose;

    /**
     * @var int
     */
    private $goalFor;

    /**
     * @var int
     */
    private $goalAgainst;

    /**
     * @var int
     */
    private $goalDiff;

    /**
     * @var float
     */
    private $championshipPercentage = 0;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Standing
     */
    public function setId(int $id): Standing
    {
        $this->id = $id;
        return $this;
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
     * @return Standing
     */
    public function setTotalPoints(int $totalPoints): Standing
    {
        $this->totalPoints = $totalPoints;
        return $this;
    }

    /**
     * @return int
     */
    public function getMatch(): int
    {
        return $this->match;
    }

    /**
     * @param int $match
     * @return Standing
     */
    public function setMatch(int $match): Standing
    {
        $this->match = $match;
        return $this;
    }

    /**
     * @return int
     */
    public function getWin(): int
    {
        return $this->win;
    }

    /**
     * @param int $win
     * @return Standing
     */
    public function setWin(int $win): Standing
    {
        $this->win = $win;
        return $this;
    }

    /**
     * @return int
     */
    public function getDraw(): int
    {
        return $this->draw;
    }

    /**
     * @param int $draw
     * @return Standing
     */
    public function setDraw(int $draw): Standing
    {
        $this->draw = $draw;
        return $this;
    }

    /**
     * @return int
     */
    public function getLoose(): int
    {
        return $this->loose;
    }

    /**
     * @param int $loose
     * @return Standing
     */
    public function setLoose(int $loose): Standing
    {
        $this->loose = $loose;
        return $this;
    }

    /**
     * @return int
     */
    public function getGoalFor(): int
    {
        return $this->goalFor;
    }

    /**
     * @param int $goalFor
     * @return Standing
     */
    public function setGoalFor(int $goalFor): Standing
    {
        $this->goalFor = $goalFor;
        return $this;
    }

    /**
     * @return int
     */
    public function getGoalAgainst(): int
    {
        return $this->goalAgainst;
    }

    /**
     * @param int $goalAgaints
     * @return Standing
     */
    public function setGoalAgainst(int $goalAgaints): Standing
    {
        $this->goalAgainst = $goalAgaints;
        return $this;
    }

    /**
     * @return int
     */
    public function getGoalDiff(): int
    {
        return $this->goalDiff;
    }

    /**
     * @param int $goalDiff
     * @return Standing
     */
    public function setGoalDiff(int $goalDiff): Standing
    {
        $this->goalDiff = $goalDiff;
        return $this;
    }

    /**
     * @return float
     */
    public function getChampionshipPercentage()
    {
        return $this->championshipPercentage;
    }

    /**
     * @param float $championshipPercentage
     * @return Standing
     */
    public function setChampionshipPercentage(float $championshipPercentage): Standing
    {
        $this->championshipPercentage = round($championshipPercentage);
        return $this;
    }

}
