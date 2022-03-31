<?php

namespace App\Entities;

use DateTime;

class ClubMatch
{
    /**
     * @var string
     */
    private $matchUuid;

    /**
     * @var Club
     */
    private $winner;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var Club
     */
    private $home;

    /**
     * @var Club
     */
    private $away;

    /**
     * @var int
     */
    private $awayPoint = 0;

    /**
     * @var int
     */
    private $homePoint = 0;

    /**
     * @var int
     */
    private $homeWinPercentage = 0;

    /**
     * @var int
     */
    private $awayWinPercentage = 0;

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return ClubMatch
     */
    public function setDate(DateTime $date): ClubMatch
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return Club| null
     */
    public function getHome(): ?Club
    {
        return $this->home;
    }

    /**
     * @param Club $home
     * @return ClubMatch
     */
    public function setHome(Club $home): ClubMatch
    {
        $this->home = $home;
        return $this;
    }

    /**
     * @return Club|null
     */
    public function getAway(): ?Club
    {
        return $this->away;
    }

    /**
     * @param Club $away
     * @return ClubMatch
     */
    public function setAway(Club $away): ClubMatch
    {
        $this->away = $away;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMatchUuid(): ?string
    {
        return $this->matchUuid;
    }

    /**
     * @param string $matchUuid
     * @return ClubMatch
     */
    public function setMatchUuid(string $matchUuid): ClubMatch
    {
        $this->matchUuid = $matchUuid;
        return $this;
    }

    /**
     * @return Club|null
     */
    public function getWinner(): ?Club
    {
        return $this->winner;
    }

    /**
     * @param Club $winner
     * @return ClubMatch
     */
    public function setWinner(Club $winner): ClubMatch
    {
        $this->winner = $winner;
        return $this;
    }

    /**
     * @return int
     */
    public function getAwayPoint(): int
    {
        return $this->awayPoint;
    }

    /**
     * @param int $awayPoint
     * @return ClubMatch
     */
    public function addAwayPoint(int $awayPoint): ClubMatch
    {
        $this->awayPoint = $this->getAwayPoint() + $awayPoint;
        return $this;
    }

    /**
     * @return int
     */
    public function getHomePoint(): int
    {
        return $this->homePoint;
    }

    /**
     * @param int $homePoint
     * @return ClubMatch
     */
    public function addHomePoint(int $homePoint): ClubMatch
    {
        $this->homePoint = $this->getHomePoint() + $homePoint;
        return $this;
    }

    /**
     * @return int
     */
    public function getHomeWinPercentage(): int
    {
        return $this->homeWinPercentage;
    }

    /**
     * @param int $homeWinPercentage
     * @return ClubMatch
     */
    public function setHomeWinPercentage(int $homeWinPercentage): ClubMatch
    {
        $this->homeWinPercentage = $homeWinPercentage;
        return $this;
    }

    /**
     * @return int
     */
    public function getAwayWinPercentage(): int
    {
        return $this->awayWinPercentage;
    }

    /**
     * @param int $awayWinPercentage
     * @return ClubMatch
     */
    public function setAwayWinPercentage(int $awayWinPercentage): ClubMatch
    {
        $this->awayWinPercentage = $awayWinPercentage;
        return $this;
    }

}
